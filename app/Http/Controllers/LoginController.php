<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Exception;
use App\Models\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Helpers\DataFilter;
use App\Helpers\Validations;


class LoginController extends Controller
{
    public function login(Request $req)
    {
        $data_filter = new DataFilter();
        $email = $data_filter->check_input($req->email);
        $password = $data_filter->check_input($req->password);

        $validate = new Validations();
        $validation_error = $validate->login_validate($email, $password);
        if ($validation_error !== "") {
            return response()->json([
                "message" => $validation_error,
                "code" => 201,
            ]);
        }
        try {
            $user = Users::where('email', $req->email)->get();
            if (Hash::check($req->password, $user[0]->password)) {
                $api_token = $user[0]->is_admin === "Yes"
                    ? "78357" . Str::random(60) . strval($user[0]->id) :
                    "14219" . Str::random(60) . strval($user[0]->id);
                $token = new Token;
                $token->api_token = $api_token;
                $token->user_id = $user[0]->id;
                $token->save();
                return response()->json([
                    "api_key" => $api_token,
                    "code" => 200,
                    "user" => $user[0],
                ]);
            } else {
                return response()->json(
                    [
                        "message" => "Invalid Username or password",
                        "code" => 201,
                    ]
                );
            }
        } catch (Exception $e) {

            return response()->json(
                [
                    "message" => "Invalid Request",
                    "code" => 201,
                ]
            );
        }
    }
}
