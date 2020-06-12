<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Exception;
use Illuminate\Support\Facades\File;

class DeleteArticleController extends Controller
{
    public function delete(Request $req)
    {
        try {
            $article = Article::find($req->id);
            File::delete('upload/images/' . $article->image_name);
            $article = Article::find($req->id)->delete();

            return response()->json([
                "code" => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "code" => 419,
            ]);
        }
    }
}
