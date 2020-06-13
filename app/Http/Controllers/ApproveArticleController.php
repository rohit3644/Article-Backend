<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use Exception;
use Illuminate\Contracts\Logging\Log;

class ApproveArticleController extends Controller
{
    public function approve(Request $req)
    {
        try {
            $response = new Response();
            $article = Article::find($req->id);
            $article->is_approved = "Yes";
            $article->save();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            $log = new Log();
            $log->error($msg["message"]);
            return response()->json($msg);
        }
    }
}
