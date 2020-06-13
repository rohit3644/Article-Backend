<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Contracts\Logging\Log;

class RegistrationController extends Controller
{
    public function register(RegisterRequest $req)
    {
        try {
            $response = new Response();
            $user = new Users;
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->mobile_no = $req->mobile;
            $user->is_admin = "No";
            $user->save();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(200);
            $log = new Log();
            $log->error($msg["message"]);
            return response()->json($msg);
        }
    }
}
