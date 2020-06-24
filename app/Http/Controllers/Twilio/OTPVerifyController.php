<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use Exception;
use Twilio\Rest\Client;
use App\Http\Requests\OTPVerifyRequest;
use Illuminate\Support\Facades\Log;
// this class is used to verify otp using twilio
class OTPVerifyController extends Controller
{
    public function verify(OTPVerifyRequest $req)
    {
        try {
            $response = new Response();
            $number = '+91' . $req->mobile;
            /* Get credentials from .env */
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio_sid = getenv("TWILIO_SID");
            $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
            // create new Client
            $twilio = new Client($twilio_sid, $token);
            // verify the OTP
            $verification = $twilio->verify->v2->services($twilio_verify_sid)
                ->verificationChecks
                ->create($req['otp'], array('to' => $number));
            if ($verification->valid) {
                $msg = $response->response(200);
                return response()->json($msg);
            }
            $msg = $response->response(422);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
