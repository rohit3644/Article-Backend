<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\ArticleComment;
use Exception;
use App\Models\Article;
use App\Models\Users;
use App\Mail\CommentSubmitMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AddCommentRequest;
use Illuminate\Contracts\Logging\Log;

class CommentController extends Controller
{
    public function add(AddCommentRequest $req)
    {

        try {
            $response = new Response();
            $comments = new ArticleComment();
            $comments->comments = $req->comment;
            $comments->article_id = $req->articleId;
            $comments->is_approved = $req->isApproved;
            $commentUser = "";
            if ($req->userId > 0) {
                $comments->user_id = $req->userId;
                $commentUser = Users::find($req->userId)->name;
            } else {
                $commentUser = "Guest";
            }
            $comments->save();
            $article =  Article::find($req->articleId);
            $article['comments'] = $article->comments;
            foreach ($article->comments as $comments) {
                if (is_null($comments->user_id)) {
                    $comments["user"] = "Guest";
                } else {
                    $user = Users::find($comments->user_id);
                    $comments["user"] = $user->name;
                }
            }
            $data = [
                "articleName" => $req->articleName,
                "comment" => $req->comment,
                "commentUser" => $commentUser,
                "articleUser" => $req->articleUser,
            ];
            if (!is_null($req->articleMail)) {
                Mail::to($req->articleMail)->send(new CommentSubmitMail($data));
            }

            $msg = $response->response(200, $article);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            $log = new Log();
            $log->error($msg["message"]);
            return response()->json($msg);
        }
    }
}
