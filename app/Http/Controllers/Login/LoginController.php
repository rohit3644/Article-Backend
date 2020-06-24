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
// this class is used for login
class LoginController extends Controller
{
    public function login(LoginRequest $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $user = Users::where('email', $req->email)->get();
            if (Hash::check($req->password, $user[0]->password)) {
                // generating api token
                $api_token = $user[0]->is_admin === "Yes"
                    ? "78357" . Str::random(60) . strval($user[0]->id) :
                    "14219" . Str::random(60) . strval($user[0]->id);
                $token = new Token;
                $token->api_token = Hash::make($api_token);
                $token->user_id = $user[0]->id;
                $token->save();
                $data = [
                    "api_key" => $api_token,
                    "user" => $user[0],
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

    public function googleAuth(Request $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $user = Socialite::driver('google')->userFromToken($req->token);
            $checkProvider = Users::where('provider_id', $user->id)->first();
            if (is_null($checkProvider)) {
                $usertable = new Users;
                $usertable->name = $user->name;
                $usertable->email = $user->email;
                $usertable->is_Admin = "No";
                $usertable->provider_id = $user->id;
                $usertable->provider_type = "Google";
                $usertable->save();
                $api_token = "14219" . Str::random(60) . strval($usertable->id);
                $token = new Token;
                $token->api_token = Hash::make($api_token);
                $token->user_id = $usertable->id;
                $token->save();
                $data = [
                    "api_key" => $api_token,
                    "user" => $user,
                ];
                // Commit Transaction
                DB::commit();
                $msg = $response->response(200, $data);
                return response()->json($msg);
            } else {
                $api_token = "14219" . Str::random(60) . strval($checkProvider->id);
                $token = new Token;
                $token->api_token = Hash::make($api_token);
                $token->user_id = $checkProvider->id;
                $token->save();
                $data = [
                    "api_key" => $api_token,
                    "user" => $user,
                ];
                // Commit Transaction
                DB::commit();
                $msg = $response->response(200, $data);
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
}
