<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use App\Http\Requests\OTPRequest;
use Exception;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
// this class is used to send otp using twilio
class OTPSendController extends Controller
{
    public function send(OTPRequest $req)
    {
        try {
            $response = new Response();
            $number = '+91' . $req->mobile;
            /* Get credentials from .env */
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio_sid = getenv("TWILIO_SID");
            $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
            // create new client
            $twilio = new Client($twilio_sid, $token);
            // send the OTP through sms
            $twilio->verify->v2->services($twilio_verify_sid)
                ->verifications
                ->create($number, "sms");
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
