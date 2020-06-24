<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
// this class is used to delete articles
class DeleteArticleController extends Controller
{
    public function delete(Request $req)
    {
        try {
            $response = new Response();
            $article = Article::find($req->id);
            // delete the respective image file also
            File::delete('upload/images/' . $article->image_name);
            $article = Article::find($req->id)->delete();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
