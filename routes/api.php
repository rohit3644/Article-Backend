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

Route::post('/update-article', 'UpdateArticleController@update'); // both

Route::post('/delete-article', 'DeleteArticleController@delete');

Route::post('/read-more', 'ReadMoreController@getId'); // both

Route::group(['middleware' => 'admin'], function () {

    Route::post('/approve-article', 'ApproveArticleController@approve'); //admin

    Route::post('/edit-comment', 'EditCommentController@edit'); // admin

    Route::post('/delete-comment', 'DeleteCommentController@delete'); //admin

    Route::post('/approve-comment', 'ApproveCommentController@approve'); //admin

});


Route::group(['middleware' => 'user'], function () {


    Route::post('/add-article', 'AddArticleController@addArticle'); // user

    Route::post('/add-comment', 'CommentController@add'); // user

    Route::post('/contact', 'ContactController@contact'); // user

});
