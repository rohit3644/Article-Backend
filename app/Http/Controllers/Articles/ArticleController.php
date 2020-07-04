<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Log;

use App\Models\Category;
use App\Helpers\CategoryId;
use App\Helpers\MultipleInsert;
use App\Models\ArticleCategory;
use Illuminate\Support\Facades\Mail;
use App\Mail\ArticleSubmitMail;
use App\Http\Requests\AddArticleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Requests\UpdateArticleRequest;



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
            // check if the user_id is null then it is a Guest user
            // else get the user data from DB
            foreach ($articles as $article) {
                $article->image_name = env('ASSET_URL') . '/upload/images/' . $article->image_name;
                $article['category'] = $article->category;
                $article['comments'] = $article->comments;
                $article['articleUser'] = $article->user;

                foreach ($article->comments as $comments) {
                    if (is_null($comments->user_id)) {
                        $comments['user'] = 'Guest';
                    } else {
                        $user = Users::find($comments->user_id);
                        $comments['user'] = $user->name;
                    }
                }
            }

            $data = [
                'articles' => $articles,
                'users' => $users,
            ];
            $msg = $response->response(200, $data);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }

    // this function is used to add article, handle image naming and storing
    // sending mail to the admin
    public function addArticle(AddArticleRequest $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $article = new Article;
            $article->title = $req->title;
            $article->content = $req->content;
            $article->author_name = $req->authorName;
            // handling image file
            if ($req->hasFile('image')) {
                $file = $req->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                Storage::disk('public')->put($fileName,  File::get($file));
                $article->image_name = $fileName;
            }
            $article->is_approved = $req->isApproved;
            if ($req->userId > 0) {
                $article->user_id = $req->userId;
            }
            $article->save();

            // getting the article categories
            $category_data = Category::select('id', 'category')->get();
            $get_category_id = new CategoryId();
            $category_id = $get_category_id->get_id(explode(',', $req->selectedCategory), $category_data);
            $multiple_insert = new MultipleInsert();
            $data = $multiple_insert->multiple_insert($category_id, $article->id);
            ArticleCategory::insert($data);

            // mailing the admin
            $admin = Users::select('email')->where('is_admin', 'Yes')->get();
            $data = $req;
            Mail::to($admin[0]->email)->send(new ArticleSubmitMail($data));

            // Commit Transaction
            DB::commit();

            // response
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            // Logging exception
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }

    // this function is used to get all the articles, get the url for the images
    // and getting category, comments and Author of article through Eloquent Relationship
    public function get(Request $req)
    {
        try {
            $response = new Response();
            $articles =  Article::select('id', 'title', 'content', 'author_name', 'image_name', 'is_approved', 'user_id')->get();
            // iterate through all the articles in paginated data
            // and get the image link, category,comments and Author
            // check if the user_id is null then it is a Guest user
            // else get the user data from DB
            foreach ($articles as $article) {
                // link of the image
                $article->image_name = env('ASSET_URL') . '/upload/images/' . $article->image_name;
                $article['category'] = $article->category;
                $article['comments'] = $article->comments;
                $article['articleUser'] = $article->user;

                foreach ($article->comments as $comments) {
                    // check is user is guest or a registered user
                    if (is_null($comments->user_id)) {
                        $comments['user'] = 'Guest';
                    } else {
                        $user = Users::find($comments->user_id);
                        $comments['user'] = $user->name;
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

    // this function is used to approve article by Admin
    public function approve(Request $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $article = Article::find($req->id);
            $article->is_approved = 'Yes';
            $article->save();
            // Commit Transaction
            DB::commit();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
    // delete the articles
    public function delete(Request $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $article = Article::find($req->id);
            // delete the respective image file also
            File::delete('upload/images/' . $article->image_name);
            $article = Article::find($req->id)->delete();
            // Commit Transaction
            DB::commit();

            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }

    public function getSpecific(Request $req)
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
                    $comments['user'] = 'Guest';
                } else {
                    $user = Users::find($comments->user_id);
                    $comments['user'] = $user->name;
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
                    $comments['user'] = 'Guest';
                } else {
                    $user = Users::find($comments->user_id);
                    $comments['user'] = $user->name;
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

    public function update(UpdateArticleRequest $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $article = Article::find($req->articleId);
            $article->title = $req->title;
            $article->content = $req->content;
            $article->author_name = $req->authorName;

            if (isset($req->image)) {
                $file = $req->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                Storage::disk('public')->put($fileName,  File::get($file));
                $article->image_name = $fileName;
            }
            $article->save();

            // delete the exisiting categories and insert new categories
            $articleCategory = ArticleCategory::where('article_id', $req->articleId)->delete();

            $category_data = Category::select('id', 'category')->get();
            $get_category_id = new CategoryId();
            $category_id = $get_category_id->get_id(explode(',', $req->selectedCategory), $category_data);
            $multiple_insert = new MultipleInsert();
            $data = $multiple_insert->multiple_insert($category_id, $article->id);
            ArticleCategory::insert($data);

            // Commit Transaction
            DB::commit();

            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }

    public function userArticle(Request $req)
    {
        try {
            $response = new Response();
            $articles =  Article::where('user_id', $req->id)->paginate();

            // get the image link, category,comments and Author
            // check if the user_id is null then it is a Guest user
            // else get the user data from DB
            foreach ($articles as $article) {
                $article->image_name = env('ASSET_URL') . '/upload/images/' . $article->image_name;
                $article['category'] = $article->category;
                $article['comments'] = $article->comments;
                $article['articleUser'] = $article->user;
                foreach ($article->comments as $comments) {
                    if (is_null($comments->user_id)) {
                        $comments['user'] = 'Guest';
                    } else {
                        $user = Users::find($comments->user_id);
                        $comments['user'] = $user->name;
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
