<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/article', 'ArticleController@index');

Route::post('/register', 'RegistrationController@register');

Route::post('/login', 'LoginController@login');

Route::post('/add-article', 'AddArticleController@addArticle');

Route::post('/update-article', 'UpdateArticleController@update');

Route::post('/delete-article', 'DeleteArticleController@delete');

Route::post('/approve-article', 'ApproveArticleController@approve');

Route::post('/read-more', 'ReadMoreController@getId');

Route::post('/add-comment', 'CommentController@add');

Route::post('/edit-comment', 'EditCommentController@edit');

Route::post('/delete-comment', 'DeleteCommentController@delete');

Route::post('/approve-comment', 'ApproveCommentController@approve');

Route::post('/contact', 'ContactController@contact');
