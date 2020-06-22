<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Log;
// this class is used to get user specific articles
class UserArticleController extends Controller
{
    public function index(Request $req)
    {
        try {
            $response = new Response();
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
            $msg = $response->response(200, $articles);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500, $articles);
            Log::error($msg["message"]);
            return response()->json($msg);
        }
    }
}
