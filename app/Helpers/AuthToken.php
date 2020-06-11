<?php

namespace App\Helpers;

use App\Models\Token;
use Illuminate\Support\Facades\Hash;

class AuthToken
{
    public function isValid($reqToken, $id)
    {

        $tokens = Token::select('api_token', 'user_id')->get();
        foreach ($tokens as $token) {
            if (Hash::check($reqToken, $token->api_token) && $token->user_id === $id) {
                return true;
            }
        }

        return false;
    }
}
