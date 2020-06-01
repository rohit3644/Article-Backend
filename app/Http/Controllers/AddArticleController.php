<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Helpers\CategoryId;
use App\Helpers\MultipleInsert;
use App\Models\ArticleCategory;
use Exception;

class AddArticleController extends Controller
{
    public function addArticle(Request $req)
    {
        try {
            $article = new Article;
            $article->title = $req->title;
            $article->content = $req->content;
            $article->author_name = $req->authorName;
            if ($req->hasFile('image')) {
                $file = $req->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $file->move('upload/images/', $fileName);
                $article->image_name = $fileName;
            }
            $article->is_approved = $req->isApproved;
            if ($req->userId > 0) {
                $article->user_id = $req->userId;
            }
            $article->save();

            $category_data = Category::select('id', 'category')->get();
            $get_category_id = new CategoryId();
            $category_id = $get_category_id->get_id(explode(",", $req->selectedCategory), $category_data);
            $multiple_insert = new MultipleInsert();
            $data = $multiple_insert->multiple_insert($category_id, $article->id);
            ArticleCategory::insert($data);
            return response()->json([
                "message" => "Successfully added the article",
                "code" => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error in submitting the article",
                "code" => 201,
            ]);
        }
    }
}
