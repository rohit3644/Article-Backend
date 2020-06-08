<?php

namespace App\Helpers;

use \Illuminate\Support\Facades\Validator;

class Validations
{
    public function login_validate($email, $password)
    {
        $input = ['email' => $email, 'password' => $password];
        $rules = [
            'email' => 'bail|required|email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($input, $rules);
        $msg = "";
        if ($validator->fails()) {
            $msg = $validator->messages()->first();
        }
        return $msg;
    }

    public function register_validate($name, $email, $password, $mobile)
    {
        $input = [
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'mobile' => $mobile,
        ];
        $rules = [
            'email' => 'bail|required|email|unique:users',
            'password' => 'required|min:8',
            'name' => ['required', 'regex:/^[a-zA-Z ]*$/'],
            'mobile' => ['required', 'regex:/^[0-9]{10}$/'],
        ];

        $validator = Validator::make($input, $rules);
        $msg = "";
        if ($validator->fails()) {
            $msg = $validator->messages()->first();
        }
        return $msg;
    }

    public function add_article_validate($title, $content, $authorName, $selectedCategory, $image)
    {
        $input = [
            'title' => $title,
            'content' => $content,
            'authorName' => $authorName,
            'selectedCategory' => $selectedCategory,
            'image' => $image,
        ];

        // Tell the validator that this file should be an image
        $rules = array(
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:2500',
            'title' => 'required',
            'content' => 'required',
            'authorName' => 'required',
            'selectedCategory' => 'required',
        );

        // Now pass the input and rules into the validator
        $validator = Validator::make($input, $rules);

        $msg = "";
        if ($validator->fails()) {
            $msg = $validator->messages()->first();
        }
        return $msg;
    }

    public function update_article_validate($title, $content, $authorName, $selectedCategory, $image = null)
    {
        $input = [
            'title' => $title,
            'content' => $content,
            'authorName' => $authorName,
            'selectedCategory' => $selectedCategory,
        ];

        $rules = array(
            'title' => 'required',
            'content' => 'required',
            'authorName' => 'required',
            'selectedCategory' => 'required',
        );

        $validator = Validator::make($input, $rules);

        $msg = "";
        if ($validator->fails()) {
            $msg = $validator->messages()->first();
        }

        $imageError = "";
        if (!is_null($image)) {
            $fileArray = array('image' => $image);

            // Tell the validator that this file should be an image
            $rules = array(
                'image' => 'mimes:jpeg,jpg,png,gif|required|max:2500' // max 2500kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);


            if ($validator->fails()) {
                $imageError = "Error in Image Upload";
            }
        }

        $reMsg = "";

        if (
            $msg !== "" ||
            $imageError !== ""
        ) {


            if ($msg !== "") {
                $reMsg = $msg;
            } else if ($imageError !== "") {
                $reMsg = $imageError;
            }
        }
        return $reMsg;
    }

    public function update_comment_validate($newComment)
    {
        $input = [
            'newComment' => $newComment,
        ];
        $rules = [
            'newComment' => 'required',
        ];

        $validator = Validator::make($input, $rules);
        $msg = "";
        if ($validator->fails()) {
            $msg = $validator->messages()->first();
        }
        return $msg;
    }

    public function contact_validate($email, $name, $message)
    {

        $input = [
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ];
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ];

        $validator = Validator::make($input, $rules);
        $msg = "";
        if ($validator->fails()) {
            $msg = $validator->messages()->first();
        }
        return $msg;
    }
}
