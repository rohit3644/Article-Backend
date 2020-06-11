<?php


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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/article', 'ArticleController@index');

Route::post('/register', 'RegistrationController@register');

Route::post('/login', 'LoginController@login');

Route::post('/update-article', 'UpdateArticleController@update'); // both

Route::post('/delete-article', 'DeleteArticleController@delete');

Route::post('/read-more', 'ReadMoreController@getId'); // both

Route::post('/get-article', 'GetArticleController@get');

Route::get('/all-articles', 'AllArticlesController@get');

// Route::group(['middleware' => ['admin', 'authKey']], function () {

Route::post('/approve-article', 'ApproveArticleController@approve');

Route::post('/edit-comment', 'EditCommentController@edit'); // admin

Route::post('/delete-comment', 'DeleteCommentController@delete'); //admin

Route::post('/approve-comment', 'ApproveCommentController@approve'); //admin

// });

Route::post('/add-article', 'AddArticleController@addArticle'); // user

Route::post('/add-comment', 'CommentController@add');

Route::post('/contact', 'ContactController@contact');

// Route::group(['middleware' => ['user', 'authKey']], function () {
Route::post('/user-article', 'UserArticleController@index')->middleware('authKey');
// });
