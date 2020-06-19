<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Users;
use Exception;
use Illuminate\Contracts\Logging\Log;
// this class is used to get specific article
class GetArticleController extends Controller
{
    public function get(Request $req)
    {
        try {
            $response = new Response();
            $article = Article::find($req->id);
            $article->image_name = asset('/upload/images/' . $article->image_name);
            $article['category'] = $article->category;
            $article['comments'] = $article->comments;
            $article['articleUser'] = $article->user;

            foreach ($article->comments as $comments) {
                if (is_null($comments->user_id)) {
                    $comments["user"] = "Guest";
                } else {
                    $user = Users::find($comments->user_id);
                    $comments["user"] = $user->name;
                }
            }
            $msg = $response->response(200, $article);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500, $article);
            $log = new Log();
            $log->error($msg["message"]);
            return response()->json($msg);
        }
    }
}
