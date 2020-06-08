<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Exception;

class DeleteArticleController extends Controller
{
    public function delete(Request $req)
    {
        try {
            $article = Article::find($req->id)->delete();
            return response()->json([
                "message" => "Successfully deleted the article",
                "code" => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error in deleting the article",
                "code" => 201,
            ]);
        }

        // return "Hi";
    }
}
