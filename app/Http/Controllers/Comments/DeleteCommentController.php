<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\ArticleComment;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// this class is used to delete the comments
class DeleteCommentController extends Controller
{
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
}
