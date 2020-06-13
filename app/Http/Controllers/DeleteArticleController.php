<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Logging\Log;

class DeleteArticleController extends Controller
{
    public function delete(Request $req)
    {
        try {
            $response = new Response();
            $article = Article::find($req->id);
            File::delete('upload/images/' . $article->image_name);
            $article = Article::find($req->id)->delete();
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
