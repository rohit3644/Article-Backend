<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// this class is used for registration
class RegistrationController extends Controller
{
    public function register(RegisterRequest $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $user = new Users;
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->mobile_no = $req->mobile;
            $user->is_admin = "No";
            $user->save();
            // Commit Transaction
            DB::commit();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(200);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
