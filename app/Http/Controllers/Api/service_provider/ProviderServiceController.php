<?php

namespace App\Http\Controllers\Api\service_provider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Service;
use App\servicesSpanish;
use App\Servicetype;
use App\service_typesSpanish;
use App\Serviceprovider;
use App\Sptostype;
use Imageresize;
use DB;
use URL;

class ProviderServiceController extends Controller
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

    public function getServiceList(Request $request)
    {
	  	$lang = $request->lang;
      $services = Service::get(['id','name']);
      foreach ($services as  $service_id)
      {
         if($lang == 'es')
         {
            $spanish = servicesSpanish::where('service_id',$service_id->id)->first();
            $service_id->name = $spanish->name;
         }
      }
      if(count($services)>0)
      {
      	 return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all Services","payload"=> $services]);
      }
      else
      {
      	  return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no Service"]);
      }	
    }
//---------------------Get full details of service type --------------------------//

   public function getServiceTypeList(Request $request)
   {

      $input = $request->all();
      $lang  = $request->lang;
      $rules = array('serviceId' => 'required');
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else
      {
          $servicetypes = Servicetype::where('service_id', $input['serviceId'])->get();
          foreach ($servicetypes as $servicetype) 
          {  
             if($lang == 'es')
             {
                $spnish = service_typesSpanish::where('servicetype_id',$servicetype->id)->first();
                $servicetype->name = $spnish->name;
                $servicetype->description = $spnish->description;
             }
             $baseUrl = URL::to('/');
             $servicetype->image = $baseUrl.'/'.'normal_images/'.$servicetype->image;
          }
          if(count($servicetypes)>0)
          {
            return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all Service type","payload"=> $servicetypes]);
          }
          else
          {

            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no Service type"]);
          }
      } 
    }
//--------------------------Get service type Name only---------------------------------//

    public function getServiceTypeName(Request $request){

    $input = $request->all();
    $lang  = $request->lang;

    $rules = array(
      'serviceId' => 'required'
    );

      $validator = Validator::make($input, $rules);

      if ($validator->fails()) {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else
      {
         $servicetypes = Servicetype::where('service_id', $input['serviceId'])->where('status' ,'1')->get(['id','name']);
         foreach ($servicetypes as $servicetype) 
          { 
            if($lang == 'es')
            {
              $spnish = service_typesSpanish::where('servicetype_id',$servicetype->id)->first();
              $servicetype->name = $spnish->name;
            }
          }

          if(count($servicetypes)>0)
          {

             return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "Name of Service type","payload"=> $servicetypes]);
          }
          else
          {

              return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no Service type"]);
          }
      }     
    }

//----------------------Get list Of All Service type-------------------------//

 public function getAllServiceTypeList(Request $request)
  {
      $lang  = $request->lang;
      $servicetypes = Servicetype::where('status', 1)->get(['id','image','name','description']);
      foreach ($servicetypes as $servicetype) 
      { 
        if($lang == 'es')
        {
          $spnish = service_typesSpanish::where('servicetype_id',$servicetype->id)->first();
          $servicetype->name = $spnish->name;
          $servicetype->description = $spnish->description;
        }
        $baseUrl = URL::to('/');
        $servicetype->image = $baseUrl.'/'.'normal_images/'.$servicetype->image;
      }
      if(count($servicetypes)>0)
      {
        return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all Service Types","payload"=>$servicetypes]);
      }
      else
      {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no Service type"]);   
      }    
  }

}
