<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Users;
use App\Helpers\CategoryId;
use App\Helpers\MultipleInsert;
use App\Helpers\Response;
use App\Models\ArticleCategory;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\ArticleSubmitMail;
use App\Http\Requests\AddArticleRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

// this class is used to add article

class AddArticleController extends Controller
{
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
            $category_id = $get_category_id->get_id(explode(",", $req->selectedCategory), $category_data);
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
}
