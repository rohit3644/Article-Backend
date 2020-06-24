<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Exception;
use App\Models\ArticleComment;
use App\Http\Requests\EditCommentRequest;
use Illuminate\Support\Facades\Log;
// this class is used to edit comments
class EditCommentController extends Controller
{
    public function edit(EditCommentRequest $req)
    {
        try {
            $response = new Response();
            $comment = ArticleComment::find($req->id);
            $comment->comments = $req->newComment;
            $comment->save();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
