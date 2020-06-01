<?php

namespace App\Helpers;

class Validations
{
    public function login_validate($email, $password)
    {
        $email_error = "";
        $password_error = "";
        $email_error = (empty($email)
            ? "Email cannot be empty" : (!filter_var($email, FILTER_VALIDATE_EMAIL)
                ? "Invalid Email" : ""));

        $password_error = (empty($password) ? "Password cannot be null"
            : (strlen($password) < 8 ? "Password should be atleast 8 characters"
                : ""));

        $msg = "";
        if (
            $email_error !== "" ||
            $password_error !== ""
        ) {
            if ($email_error !== "") {
                $msg = $email_error;
            } else if ($password_error !== "") {
                $msg = $password_error;
            }
        }
        return $msg;
    }

    public function register_validate($name, $email, $password, $mobile)
    {
        $name_error = "";
        $email_error = "";
        $password_error = "";
        $mobile_error = "";

        $name_error = (empty($name) ? "Name cannot be Null"
            : (!preg_match("/^[a-zA-Z ]*$/", $name)
                ? "Only Letters and whitespace allowed" : ""));

        $email_error = (empty($email)
            ? "Email cannot be empty" : (!filter_var($email, FILTER_VALIDATE_EMAIL)
                ? "Invalid Email" : ""));

        $password_error = (empty($password) ? "Password cannot be null"
            : (strlen($password) < 8 ? "Password should be atleast 8 characters"
                : ""));

        $mobile_error = (empty($mobile) ? "Mobile number cannot be empty" : (!preg_match("/^[0-9]{10}$/", $mobile) ? "Invalid mobile number" : ""));

        $msg = "";
        if (
            $name_error !== "" ||
            $email_error !== "" ||
            $password_error !== "" ||
            $mobile_error !== ""
        ) {


            if ($name_error !== "") {
                $msg = $name_error;
            } else if ($email_error !== "") {
                $msg = $email_error;
            } else if ($password_error !== "") {
                $msg = $password_error;
            } else if ($mobile_error !== "") {
                $msg = $mobile_error;
            }
        }
        return $msg;
    }
}
