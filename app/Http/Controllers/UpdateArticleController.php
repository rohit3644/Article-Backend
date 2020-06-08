<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Category;
use App\Helpers\CategoryId;
use App\Helpers\MultipleInsert;
use App\Models\ArticleCategory;
use Exception;

use App\Helpers\DataFilter;
use App\Helpers\Validations;

class UpdateArticleController extends Controller
{
    public function update(Request $req)
    {
        $data_filter = new DataFilter();
        $title = $data_filter->check_input($req->title);
        $content = $data_filter->check_input($req->content);
        $authorName = $data_filter->check_input($req->authorName);
        $selectedCategory = $data_filter->check_input($req->selectedCategory);

        $validate = new Validations();
        if (isset($req->image)) {
            $validation_error = $validate
                ->update_article_validate($title, $content, $authorName, $selectedCategory, $req->file('image'));
        } else {
            $validation_error = $validate
                ->update_article_validate($title, $content, $authorName, $selectedCategory);
        }
        if ($validation_error !== "") {
            return response()->json([
                "message" => $validation_error,
                "code" => 201,
            ]);
        }

        try {
            $article = Article::find($req->articleId);
            $article->title = $title;
            $article->content = $content;
            $article->author_name = $authorName;

            if (isset($req->image)) {
                $file = $req->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $file->move('upload/images/', $fileName);
                $article->image_name = $fileName;
            }
            $article->save();

            $articleCategory = ArticleCategory::where('article_id', $req->articleId)->delete();

            $category_data = Category::select('id', 'category')->get();
            $get_category_id = new CategoryId();
            $category_id = $get_category_id->get_id(explode(",", $selectedCategory), $category_data);
            $multiple_insert = new MultipleInsert();
            $data = $multiple_insert->multiple_insert($category_id, $article->id);
            ArticleCategory::insert($data);

            return response()->json([
                "message" => "Successfully updated the article",
                "code" => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error in updating the article",
                "code" => 201,
            ]);
        }
    }
}
