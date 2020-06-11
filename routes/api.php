<?php

Route::get('/article', 'ArticleController@index');

Route::post('/register', 'RegistrationController@register');

Route::post('/login', 'LoginController@login');

Route::post('/update-article', 'UpdateArticleController@update')->middleware("authKey");

Route::post('/delete-article', 'DeleteArticleController@delete')->middleware("authKey");

Route::post('/read-more', 'ReadMoreController@getId');

Route::post('/get-article', 'GetArticleController@get')->middleware("authKey");

Route::get('/all-articles', 'AllArticlesController@get');

// admin specific routes

Route::post('/approve-article', 'ApproveArticleController@approve')->middleware("authKey", "admin");

Route::post('/edit-comment', 'EditCommentController@edit')->middleware("authKey", "admin");

Route::post('/delete-comment', 'DeleteCommentController@delete')->middleware("authKey", "admin");

Route::post('/approve-comment', 'ApproveCommentController@approve')->middleware("authKey", "admin");

// user specific routes

Route::post('/add-article', 'AddArticleController@addArticle');

Route::post('/add-comment', 'CommentController@add');

Route::post('/contact', 'ContactController@contact');

Route::post('/user-article', 'UserArticleController@index')->middleware("authKey", "user");
