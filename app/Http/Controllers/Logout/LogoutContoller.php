<?php

namespace App\Http\Controllers\Logout;

use Illuminate\Http\Request;
use App\Models\Token;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function logout(Request $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $id = intval(substr($req->apiToken, 65));
            $token = Token::where('user_id', $id)
                ->where('api_token', $req->apiToken)
                ->where('is_active', "Yes")->get();
            $token->is_active = "No";
            $token->save();
            // Commit Transaction
            DB::commit();
            // response
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            // Logging exception
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
