<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\Users;
use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// this class is used for article details
class ReadMoreController extends Controller
{
    public function getId(Request $req)
    {
        try {
            $response = new Response();
            $data = Article::select('id', 'title')->get();
            $id = -1;
            // get the id of matching article
            foreach ($data as $article) {
                if (strtolower($article->title) === $req->article) {
                    $id = $article->id;
                }
            }
            if ($id === -1) {
                return -1;
            }
            $userArticle = Article::find($id);
            $userArticle->image_name = $userArticle->image_name;
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
            $msg = $response->response(200, $userArticle);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500, $userArticle);
            Log::error($msg["message"]);
            return response()->json($msg);
        }
    }
}
