<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;

use App\Models\Article;
use App\Models\Category;
use App\Helpers\CategoryId;
use App\Helpers\MultipleInsert;
use App\Models\ArticleCategory;
use Exception;
use App\Helpers\Response;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
// this class is used to update an article details like title,
// content, authorName, image and category
class UpdateArticleController extends Controller
{
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
            $category_id = $get_category_id->get_id(explode(",", $req->selectedCategory), $category_data);
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
}
