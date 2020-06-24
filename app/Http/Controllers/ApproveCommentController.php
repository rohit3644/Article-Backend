<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\ArticleComment;
use Exception;
use Illuminate\Support\Facades\Log;

// this class is used to approve comments
class ApproveCommentController extends Controller
{
    public function approve(Request $req)
    {
        try {
            $response = new Response();
            $comment = ArticleComment::find($req->id);
            $comment->is_approved = "Yes";
            $comment->save();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            Log::error($msg["message"]);
            return response()->json($msg);
        }
    }
}
