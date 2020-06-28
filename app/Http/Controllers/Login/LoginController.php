<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;

use Socialite;
use App\Helpers\Response;
use App\Models\Users;
use Exception;
use App\Models\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// this class is used for app login authentication, 
// api token generation and google login using Socialite
class LoginController extends Controller
{
    // this function is used for app login
    public function login(LoginRequest $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $user = Users::where('email', $req->email)->get();
            if (Hash::check($req->password, $user[0]->password)) {
                // generating api token
                $api_token = $user[0]->is_admin === 'Yes'
                    ? '78357' . Str::random(60) . strval($user[0]->id) :
                    '14219' . Str::random(60) . strval($user[0]->id);
                // Hashing and storing the token
                $token = new Token;
                $token->api_token = $api_token;
                $token->is_active = "Yes";
                $token->user_id = $user[0]->id;
                $token->save();
                $data = [
                    'api_key' => $api_token,
                    'user' => $user[0],
                ];
                // Commit Transaction
                DB::commit();
                $msg = $response->response(200, $data);
                return response()->json($msg);
            } else {
                // Rollback Transaction
                DB::rollback();
                $msg = $response->response(422);
                return response()->json($msg);
            }
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }

    // this function is used Google Login using Socialite
    public function googleAuth(Request $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            // verify token with Socialite driver
            $user = Socialite::driver('google')->userFromToken($req->token);
            // check if user already exists
            $checkProvider = Users::where('provider_id', $user->id)->first();
            $token = new Token;
            $token->is_active = "Yes";
            // add to the DB if new User
            if (is_null($checkProvider)) {
                $usertable = new Users;
                $usertable->name = $user->name;
                $usertable->email = $user->email;
                $usertable->is_Admin = 'No';
                $usertable->provider_id = $user->id;
                $usertable->provider_type = 'Google';
                $usertable->save();
                $api_token = '14219' . Str::random(60) . strval($usertable->id);
                $token->api_token = $api_token;
                $token->user_id = $usertable->id;
                $token->save();
            }
            // else if existing user then generate token and proceed
            else {
                $api_token = '14219' . Str::random(60) . strval($checkProvider->id);
                $token->api_token = $api_token;
                $token->user_id = $checkProvider->id;
                $token->save();
            }
            $data = [
                'api_key' => $api_token,
                'user' => $user,
            ];
            // Commit Transaction
            DB::commit();
            $msg = $response->response(200, $data);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
