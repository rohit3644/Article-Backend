<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use Exception;
use App\Http\Requests\PaymentRequest;
use Illuminate\Support\Facades\Log;
// this class is used for stripe payment
class PaymentController extends Controller
{
    public function payment(PaymentRequest $req)
    {
        try {
            $response = new Response();
            \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
            $token = $req->tokenId;
            // create customer
            $customer = \Stripe\Customer::create(array(
                'name' => 'test',
                'address' => [
                    'line1' => $req->line,
                    'postal_code' => $req->postalCode,
                    'city' => $req->city,
                    'state' => $req->state,
                    'country' => $req->country,
                ],
                'source' => $token,
            ));
            // create charge
            $charge = \Stripe\Charge::create([
                'customer' => $customer->id,
                'amount' => 450 * 100,
                'currency' => 'inr',
                'description' => 'Monthly Charge',
            ]);
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
