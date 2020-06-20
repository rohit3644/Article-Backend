<?php

// get article
Route::get('/article', 'ArticleController@index');

// send otp
Route::post('/otp-send', 'OTPSendController@send');

// verify otp
Route::post('/otp-verify', 'OTPVerifyController@verify');

// registration
Route::post('/register', 'RegistrationController@register');

// login
Route::post('/login', 'LoginController@login');

// update article
Route::post('/update-article', 'UpdateArticleController@update')->middleware("authKey");

// delete article
Route::post('/delete-article', 'DeleteArticleController@delete')->middleware("authKey");

// readmore article
Route::post('/read-more', 'ReadMoreController@getId');

// get the article data to prepopulate update field
Route::post('/get-article', 'GetArticleController@get')->middleware("authKey");

// all articles for admin screen
Route::get('/all-articles', 'AllArticlesController@get');

// stripe payment
Route::post('/payment', 'PaymentController@payment');

// add article
Route::post('/add-article', 'AddArticleController@addArticle');

// add comments
Route::post('/add-comment', 'CommentController@add');

// contact us
Route::post('/contact', 'ContactController@contact');


// admin specific routes

Route::post('/approve-article', 'ApproveArticleController@approve')->middleware("authKey", "admin");

Route::post('/edit-comment', 'EditCommentController@edit')->middleware("authKey", "admin");

Route::post('/delete-comment', 'DeleteCommentController@delete')->middleware("authKey", "admin");

Route::post('/approve-comment', 'ApproveCommentController@approve')->middleware("authKey", "admin");

// user specific routes

Route::post('/user-article', 'UserArticleController@index')->middleware("authKey", "user");

// google login
Route::post('/googleAuth', 'LoginController@googleAuth');
