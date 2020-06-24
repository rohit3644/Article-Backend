<?php

// get article
Route::get('/article', '/Articles/ArticleController@index');

// send otp
Route::post('/otp-send', '/Twilio/OTPSendController@send');

// verify otp
Route::post('/otp-verify', '/Twilio/OTPVerifyController@verify');

// registration
Route::post('/register', '/Registration/RegistrationController@register');

// login
Route::post('/login', '/Login/LoginController@login');

// update article
Route::post('/update-article', '/Articles/UpdateArticleController@update')->middleware("authKey");

// delete article
Route::post('/delete-article', '/Articles/DeleteArticleController@delete')->middleware("authKey");

// readmore article
Route::post('/read-more', '/Articles/ReadMoreController@getId');

// get the article data to prepopulate update field
Route::post('/get-article', '/Articles/GetArticleController@get')->middleware("authKey");

// all articles for admin screen
Route::get('/all-articles', '/Articles/AllArticlesController@get');

// stripe payment
Route::post('/payment', '/Stripe/PaymentController@payment');

// add article
Route::post('/add-article', '/Articles/AddArticleController@addArticle');

// add comments
Route::post('/add-comment', '/Comments/CommentController@add');

// contact us
Route::post('/contact', '/Contact/ContactController@contact');


// admin specific routes

Route::post('/approve-article', '/Articles/ApproveArticleController@approve')->middleware("authKey", "admin");

Route::post('/edit-comment', '/Comments/EditCommentController@edit')->middleware("authKey", "admin");

Route::post('/delete-comment', '/Comments/DeleteCommentController@delete')->middleware("authKey", "admin");

Route::post('/approve-comment', '/Comments/ApproveCommentController@approve')->middleware("authKey", "admin");

// user specific routes

Route::post('/user-article', '/Articles/UserArticleController@index')->middleware("authKey", "user");

// google login
Route::post('/googleAuth', '/Login/LoginController@googleAuth');
