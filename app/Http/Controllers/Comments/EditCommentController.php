<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Exception;
use App\Models\ArticleComment;
use App\Http\Requests\EditCommentRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// this class is used to edit comments
class EditCommentController extends Controller
{
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
