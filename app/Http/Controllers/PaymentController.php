<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function payment(Request $req)
    {
        try {
            $response = new Response();
            \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
            $token = $req->tokenId;
            $customer = \Stripe\Customer::create(array(
                'name' => 'test',
                'address' => [
                    'line1' => '510 Townsend St',
                    'postal_code' => '98140',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'country' => 'US',
                ],
                'source' => $token,
            ));
            $charge = \Stripe\Charge::create([
                'customer' => $customer->id,
                'amount' => 60 * 100,
                'currency' => 'usd',
                'description' => 'Monthly Charge',
            ]);
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            return response()->json($msg);
        }
    }
}
