<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Redirect;
use App\Country;
use App\City;
use App\State;
use App\Zipcode;

class countryController extends Controller
{
    public function getStateList(Request $request)
    {
        $countryId = 6;
        $states = State::where('country_id',$countryId)->get(['id','name','country_id']);
        if(count($states)>0)
        {
        	 return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all States","payload"=> $states]);
        }
        else
        {

        	  return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no state"]);
        }
    }

   public function getCityList(Request $request)
   {
       $state_id = $request->get('state_id');
       $input = $request->all();
       $rules = array(
          'state_id' => 'required',
          );
       $validator = Validator::make($input, $rules);
      if($validator->fails()) 
        {
            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]); 
        }
        else
        {
            $cities = City::where('state_id',$state_id)->get(['id','name','state_id']);
            if(count($cities)>0)
            {
               return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all Cities","payload"=> $cities]);
            }
            else
            {

                return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no City"]);
            }
        }
    }
    
    public function getZipcodeList(Request $request)
    {
       $city_id = $request->get('city_id');
       $input = $request->all();
       $rules = array(
          'city_id' => 'required',
          );
       $validator = Validator::make($input, $rules);
      if($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);
         
      }
      else
      {
          $zipcodes = Zipcode::where('city_id',$city_id)->get(['id','zipcode','city_id']);
          if(count($zipcodes)>0)
          {
             return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all Zipcodes","payload"=> $zipcodes]);
          }
          else
          {

              return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no Zipcode"]);
          }
      }
    }

  //-------------------------------Get All Zipcodes ----------------------------------------------//

  public function getAllZipcodes()
  {
      $zipcodes = Zipcode::get(['zipcode']);
      if(count($zipcodes)>0)
      {
         return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "all Zipcodes","payload"=> $zipcodes]);
      }
      else
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "No Zipcodes"]);
      } 
  }
}
