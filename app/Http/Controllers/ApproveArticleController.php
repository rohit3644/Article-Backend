<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ApproveArticleController extends Controller
{
    public function approve(Request $req)
    {
        $article = Article::find($req->id);
        $article->is_approved = "Yes";
        $article->save();
        return;
    }
}
