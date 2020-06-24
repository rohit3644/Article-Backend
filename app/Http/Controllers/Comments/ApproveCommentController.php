<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\ArticleComment;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// this class is used to approve comments of the user by admin only
class ApproveCommentController extends Controller
{
    public function approve(Request $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $comment = ArticleComment::find($req->id);
            $comment->is_approved = "Yes";
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
