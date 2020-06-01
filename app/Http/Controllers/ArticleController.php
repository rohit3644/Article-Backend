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
        foreach ($articles as $article) {
            $article->image_name = asset('/upload/images/' . $article->image_name);
            $article['category'] = $article->category;
        }
        return $articles;
    }
}
