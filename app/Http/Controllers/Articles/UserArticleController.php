<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Log;
// this class is used to get all the paginated article of a specific user
class UserArticleController extends Controller
{
    public function index(Request $req)
    {
        try {
            $response = new Response();
            $articles =  Article::where("user_id", $req->id)->paginate();

            // get the image link, category,comments and Author
            foreach ($articles as $article) {
                $article->image_name = env('ASSET_URL') . '/upload/images/' . $article->image_name;
                $article['category'] = $article->category;
                $article['comments'] = $article->comments;
                $article['articleUser'] = $article->user;
            }
            // check if the user_id is null then it is a Guest user
            // else get the user data from DB
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
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
