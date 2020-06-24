<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Log;
// this class is used to get specific article according to the article id
// get the url for the image
// and getting category, comments and Author of article through Eloquent Relationship
class GetArticleController extends Controller
{
    public function get(Request $req)
    {
        try {
            $response = new Response();
            $article = Article::find($req->id);

            // get the image link, category,comments and Author
            $article->image_name = env('ASSET_URL') . '/upload/images/' . $article->image_name;
            $article['category'] = $article->category;
            $article['comments'] = $article->comments;
            $article['articleUser'] = $article->user;

            // check if the user_id is null then it is a Guest user
            // else get the user data from DB
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
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
