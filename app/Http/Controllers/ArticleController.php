<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Users;
use Exception;
use Illuminate\Contracts\Logging\Log;

// this class is used to show paginated article data to frontend
class ArticleController extends Controller
{
    public function index(Request $req)
    {
        try {
            $response = new Response();
            $articles =  Article::paginate();
            $users = Users::paginate();

            foreach ($articles as $article) {
                $article->image_name = env('ASSET_URL') . '/upload/images/' . $article->image_name;
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
            $data = [
                "articles" => $articles,
                "users" => $users,
            ];
            $msg = $response->response(200, $data);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            $log = new Log();
            $log->error($msg["message"]);
            return response()->json($msg);
        }
    }
}
