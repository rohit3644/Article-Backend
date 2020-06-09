<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArticleComment;
use Exception;
use App\Models\Article;
use App\Models\Users;

use App\Helpers\DataFilter;
use App\Mail\CommentSubmitMail;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    public function add(Request $req)
    {
        // return $req->all();

        $data_filter = new DataFilter();
        $comment = $data_filter->check_input($req->comment);
        $commentError = empty($comment) ? "Comment cannot be empty" : "";
        if ($commentError !== "") {
            return response()->json([
                "message" => $commentError,
                "code" => 201,
            ]);
        }

        try {
            $comments = new ArticleComment();
            $comments->comments = $comment;
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
            // $currentCommentId = $comments->id;
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
            // $currentComment["user"] = $req->userId > 0 ? $commentUser->name : "Guest";
            $data = [
                "articleName" => $req->articleName,
                "comment" => $comment,
                "commentUser" => $commentUser,
                "articleUser" => $req->articleUser,
            ];
            if (!is_null($req->articleMail)) {
                Mail::to($req->articleMail)->send(new CommentSubmitMail($data));
            }

            return response()->json([
                "code" => 200,
                "info" => $article,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error in adding comment",
                "code" => 201,
            ]);
        }
    }
}
