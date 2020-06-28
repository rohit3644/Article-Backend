<?php

Route::group(['middleware' => ['authKey']], function () {

    // update article
    Route::post('/update-article', 'Articles\ArticleController@update');

    // delete article
    Route::post('/delete-article', 'Articles\ArticleController@delete');

    // get the article data to prepopulate update field
    Route::post('/get-article', 'Articles\ArticleController@getSpecific');

    // admin specific routes
    Route::group(['middleware' => ['admin']], function () {

        Route::post('/approve-article', 'Articles\ArticleController@approve');

        Route::post('/edit-comment', 'Comments\CommentController@edit');

        Route::post('/delete-comment', 'Comments\CommentController@delete');

        Route::post('/approve-comment', 'Comments\CommentController@approve');
    });

    // user specific routes
    Route::post('/user-article', 'Articles\ArticleController@userArticle')->middleware('user');
});

// get article
Route::get('/article', 'Articles\ArticleController@index');

// send otp
Route::post('/otp-send', 'Twilio\TwilioController@send');

// verify otp
Route::post('/otp-verify', 'Twilio\TwilioController@verify');

// registration
Route::post('/register', 'Registration\RegistrationController@register');

// login
Route::post('/login', 'Login\LoginController@login');

// readmore article
Route::post('/read-more', 'Articles\ArticleController@getId');

// all articles for articles and corresponding categories
Route::get('/all-articles', 'Articles\ArticleController@get');

// stripe payment
Route::post('/payment', 'Stripe\PaymentController@payment');

// add article
Route::post('/add-article', 'Articles\ArticleController@addArticle');

// add comments
Route::post('/add-comment', 'Comments\CommentController@add');

// contact us
Route::post('/contact', 'Contact\ContactController@contact');

// google login
Route::post('/googleAuth', 'Login\LoginController@googleAuth');

//logout
Route::post('/logout', 'Logout\LogoutController@logout');
