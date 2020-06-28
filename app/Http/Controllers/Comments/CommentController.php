<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use App\Models\ArticleComment;
use Exception;
use App\Models\Article;
use App\Models\Users;
use App\Mail\CommentSubmitMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AddCommentRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EditCommentRequest;
use Illuminate\Http\Request;
// this class is used to add the comment to a article and
class CommentController extends Controller
{
    public function add(AddCommentRequest $req)
    {
        // Begin Transaction
        DB::beginTransaction();

        try {
            $response = new Response();
            $comments = new ArticleComment();
            $comments->comments = $req->comment;
            $comments->article_id = $req->articleId;
            $comments->is_approved = $req->isApproved;
            $commentUser = 'Guest';
            // check if the user is guest or registered
            if ($req->userId > 0) {
                $comments->user_id = $req->userId;
                $commentUser = Users::find($req->userId)->name;
            }
            $comments->save();
            $article =  Article::find($req->articleId);
            $article['comments'] = $article->comments;
            // check if the commented user is a guest or a registered user
            foreach ($article->comments as $comments) {
                if (is_null($comments->user_id)) {
                    $comments['user'] = 'Guest';
                } else {
                    $user = Users::find($comments->user_id);
                    $comments['user'] = $user->name;
                }
            }
            $data = [
                'articleName' => $req->articleName,
                'comment' => $req->comment,
                'commentUser' => $commentUser,
                'articleUser' => $req->articleUser,
            ];
            // mail the author
            if (!is_null($req->articleMail)) {
                Mail::to($req->articleMail)->send(new CommentSubmitMail($data));
            }

            // Commit Transaction
            DB::commit();

            $msg = $response->response(200, $article);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            // log exception
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }

    public function approve(Request $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $comment = ArticleComment::find($req->id);
            $comment->is_approved = 'Yes';
            $comment->save();
            // Commit Transaction
            DB::commit();

            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }

    public function delete(Request $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $comment = ArticleComment::find($req->id)->delete();
            // Commit Transaction
            DB::commit();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }

    public function edit(EditCommentRequest $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $comment = ArticleComment::find($req->id);
            $comment->comments = $req->newComment;
            $comment->save();
            // Commit Transaction
            DB::commit();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
