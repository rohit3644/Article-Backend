<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Users;
use Exception;
use App\Models\Token;

class ArticleController extends Controller
{
    public function index(Request $req)
    {
        $articles =  Article::all();
        $users = Users::all();

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
                "users" => $users,
            ]
        );
    }
}
