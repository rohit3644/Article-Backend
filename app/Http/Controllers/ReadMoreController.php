<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Article;
use Illuminate\Http\Request;

class ReadMoreController extends Controller
{
    public function getId(Request $req)
    {
        $data = Article::select('id', 'title')->get();
        $id = -1;
        foreach ($data as $article) {
            if (strtolower($article->title) === $req->article) {
                $id = $article->id;
            }
        }
        if ($id === -1) {
            return -1;
        }
        $userArticle = Article::find($id);
        $userArticle->image_name = asset('/upload/images/' . $userArticle->image_name);
        $userArticle['category'] = $userArticle->category;
        $userArticle['comments'] = $userArticle->comments;
        $userArticle['articleUser'] = $userArticle->user;

        foreach ($userArticle->comments as $comments) {
            if (is_null($comments->user_id)) {
                $comments["user"] = "Guest";
            } else {
                $user = Users::find($comments->user_id);
                $comments["user"] = $user->name;
            }
        }
        // $categories = $data->category;
        return $userArticle;
    }
}
