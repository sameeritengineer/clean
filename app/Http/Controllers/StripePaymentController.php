<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use Session;
use Stripe;
use Redirect;
class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('stripe');
    }
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    { 
        \Stripe\Stripe::setApiKey ( \Config::get('services.stripe.secret') );
        try
        {
           $data = \Stripe\Charge::create ( array (
                    "amount" => 300 * 100,
                    "currency" => "usd",
                    "source" => $request->input ( 'stripeToken' ), // obtained with Stripe.js
                    "description" => "Test payment." 
            ) );
            Session::flash ( 'success', 'Payment done successfully !' );
            return $data;
            //return Redirect::back ();
        }
        catch ( \Exception $e )
        {
            Session::flash ( 'fail-message', "Error! Please Try again." );
            return Redirect::back ();
        }
     }
}
