<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\Users;
use App\Helpers\CategoryId;
use App\Helpers\MultipleInsert;
use App\Models\ArticleCategory;
use Exception;
use Illuminate\Support\Facades\Mail;

use App\Helpers\DataFilter;
use App\Helpers\Validations;
use App\Mail\ArticleSubmitMail;

class AddArticleController extends Controller
{
    public function addArticle(Request $req)
    {

        $data_filter = new DataFilter();
        $title = $data_filter->check_input($req->title);
        $content = $data_filter->check_input($req->content);
        $authorName = $data_filter->check_input($req->authorName);
        $selectedCategory = $data_filter->check_input($req->selectedCategory);

        $validate = new Validations();
        $validation_error = $validate
            ->add_article_validate($title, $content, $authorName, $selectedCategory, $req->file('image'));
        if ($validation_error !== "") {
            return response()->json([
                "message" => $validation_error,
                "code" => 201,
            ]);
        }

        try {
            $article = new Article;
            $article->title = $title;
            $article->content = $content;
            $article->author_name = $authorName;
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
            $category_id = $get_category_id->get_id(explode(",", $selectedCategory), $category_data);
            $multiple_insert = new MultipleInsert();
            $data = $multiple_insert->multiple_insert($category_id, $article->id);
            ArticleCategory::insert($data);

            $admin = Users::select('email')->where('is_admin', 'Yes')->get();
            $data = $req;
            Mail::to($admin[0]->email)->send(new ArticleSubmitMail($data));

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
