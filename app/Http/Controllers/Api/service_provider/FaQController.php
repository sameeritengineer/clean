<?php

namespace App\Http\Controllers\Api\service_provider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\provider_faq;
use App\ProviderFaqSpanish;
use App\customer_faq;
use App\CustomerFaqSpanish;
use Response;
use Auth;

class FaQController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function formatErrors($errors)
    {
        $transformed = [];
        foreach ($errors as $field => $messages) {
            $transformed[$field] = $messages[0];
        }
        return $transformed;
    }

    public function getProviderFaQ(Request $request)
    { 
      $lang  = $request->lang;
	    $provider_faq = provider_faq::get(['id','question','answer']);
      foreach ($provider_faq as $prod_faq)
      {
        if($lang == 'es')
        {
           $spanishdeta = ProviderFaqSpanish::where('pro_faqId',$prod_faq->id)->first();
           $prod_faq->question = $spanishdeta->question;
           $prod_faq->answer   = $spanishdeta->answer;
        }
      }
      if(count($provider_faq)>0)
      {
      	 return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of FaQ's","payload"=> $provider_faq]);
      }
      else
      {
      	  return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no FaQ's"]);
      }	
    }

    public function getcustomerFaQ(Request $request)
    { 
      $lang  = $request->lang;
	    $customer_faq = customer_faq::get(['id','question','answer']);
      foreach ($customer_faq as $cust_faq)
      {
        if($lang == 'es')
        {
           $spanishdeta = CustomerFaqSpanish::where('cust_faqId',$cust_faq->id)->first();
           $cust_faq->question = $spanishdeta->question;
           $cust_faq->answer   = $spanishdeta->answer;
        }
      }
      if(count($customer_faq)>0)
      {
      	 return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of FaQ's","payload"=> $customer_faq]);
      }
      else
      {
      	  return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no FaQ's"]);
      }	
    }
}
