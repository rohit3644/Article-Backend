<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Users;

class UserArticleController extends Controller
{
    public function index(Request $req)
    {
        $articles =  Article::where("user_id", $req->id)->paginate();

        foreach ($articles as $article) {
            $article->image_name = asset('/upload/images/' . $article->image_name);
            $article['category'] = $article->category;
            $article['comments'] = $article->comments;
            $article['articleUser'] = $article->user;
        }
        foreach ($articles as $article) {
            foreach ($article->comments as $comments) {
                if (is_null($comments->user_id)) {
                    $comments["user"] = "Guest";
                } else {
                    $user = Users::find($comments->user_id);
                    $comments["user"] = $user->name;
                }
            }
        }

        return response()->json(
            [
                "articles" => $articles,
            ]
        );
    }
}
