<?php

namespace App\Helpers;

use App\Models\Token;
// this class is used to authorize the client api token
// using the server api token
class AuthToken
{
    // this functions return true if api token exists in DB
    // else returns false
    public function isValid($reqToken, $id)
    {

        $token = Token::where('user_id', $id)
            ->where('api_token', $reqToken)
            ->where('is_active', "Yes")->get();
        if (!is_null($token)) {
            return true;
        }
        return false;
    }
}
