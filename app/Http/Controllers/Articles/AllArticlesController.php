<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Log;

// this class is used to get all the articles, get the url for the images
// and getting category, comments and Author of article through Eloquent Relationship
class AllArticlesController extends Controller
{
    // this function is used to get all the articles, get the url for the images
    // and getting category, comments and Author of article through Eloquent Relationship
    public function get(Request $req)
    {
        try {
            $response = new Response();
            $articles =  Article::select('id', 'title', 'content', 'author_name', 'image_name', 'is_approved', 'user_id')->get();
            // iterate through all the articles in paginated data
            // and get the image link, category,comments and Author
            foreach ($articles as $article) {
                // link of the image
                $article->image_name = env('ASSET_URL') . '/upload/images/' . $article->image_name;
                $article['category'] = $article->category;
                $article['comments'] = $article->comments;
                $article['articleUser'] = $article->user;
            }
            // check if the user_id is null then it is a Guest user
            // else get the user data from DB
            foreach ($articles as $article) {
                foreach ($article->comments as $comments) {
                    // check is user is guest or a registered user
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
            // logging exception
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
