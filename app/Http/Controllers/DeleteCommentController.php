<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\ArticleComment;
use Exception;
use Illuminate\Support\Facades\Log;
// this class is used to delete the comments
class DeleteCommentController extends Controller
{
    public function delete(Request $req)
    {
        try {
            $response = new Response();
            $comment = ArticleComment::find($req->id)->delete();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
