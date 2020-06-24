<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Log;

// this class is used to show paginated articles and users data to frontend,
// get the url for the images
// and getting category, comments and Author of article through Eloquent Relationship
class ArticleController extends Controller
{
    public function index(Request $req)
    {
        try {
            $response = new Response();
            $articles =  Article::paginate();
            $users = Users::paginate();
            // iterate through all the articles in paginated data
            // and get the image link, category,comments and Author
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
            $data = [
                "articles" => $articles,
                "users" => $users,
            ];
            $msg = $response->response(200, $data);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
