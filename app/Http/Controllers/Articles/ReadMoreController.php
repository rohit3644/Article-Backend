<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use App\Models\Users;
use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// this class is used to get details of the requested article
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
            // if no match
            if ($id === -1) {
                return -1;
            }
            $userArticle = Article::find($id);
            // get the image link, category,comments and Author
            $userArticle->image_name = env('ASSET_URL') . '/upload/images/' . $userArticle->image_name;
            $userArticle['category'] = $userArticle->category;
            $userArticle['comments'] = $userArticle->comments;
            $userArticle['articleUser'] = $userArticle->user;

            // check if the user_id is null then it is a Guest user
            // else get the user data from DB
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
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
