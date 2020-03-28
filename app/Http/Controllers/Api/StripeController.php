<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe;
class StripeController extends Controller
{
    public function createPaymentStripe(Request $request)
    {
		\Stripe\Stripe::setApiKey ( \Config::get('services.stripe.secret') );
        try
        {
           $token = $_POST['stripeToken'];
           $response = \Stripe\Charge::create ( array (
                    "amount" => $request->amount,
                    "currency" => "usd",
                    "source" => $token, 
                    "description" => $request->description 
            ) );
            return response()->json(['status'=>true,'response'=>$response]);
        }
        catch ( \Exception $e )
        {
            return response()->json(['status'=>false,'response'=>$e->getMessage()]);
        }
    }
}
