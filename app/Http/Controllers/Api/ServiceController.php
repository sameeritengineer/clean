<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Service;
use App\Servicetype;
use App\ServicePrice;
use App\service_typesSpanish;
use App\Serviceprovider;
use App\Sptostype;
use Carbon\Carbon;
use App\User;
use App\ProviderProfile;
use App\Role;
use App\Userrole;
use App\Useraddress;
use App\Otp;
use App\Country;
use App\City;
use App\State;
use App\Providerbio;
use App\Zipcode;
use App\Approved_Bio;
use App\InstantBooking;
use App\Instant_schedule_job;
use App\SpecialRequestToCleaner;
use App\ProviderReview;
use App\servicesSpanish;
use Imageresize;
use DB;
use Auth;
use URL;
use File;
use Mail;
use Redirect;
use App\Common\Notification;

class ServiceController extends Controller
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

   public function getServiceTypeList(Request $request){

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
            // $servicetype->image = $baseUrl.'/'.'normal_images/'.$servicetype->image;
$servicetype->image =secure_url("normal_images/".$servicetype->image);
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
        $servicetype->image =secure_url("normal_images/".$servicetype->image);
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

  public function getAllPriceList(Request $request)
  {
      $input = $request->all();
      $rules = array(
        'service_id' => 'required'
      );
      $validator = Validator::make($input, $rules);
      if ($validator->fails())
      {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else
      {
        $sptostypes   = Sptostype::where('service_id',$input['service_id'])->get(['serviceprovider_id']);
        foreach($sptostypes as $type)
        {  
            $serviceproviders = Serviceprovider::where('id',$type->serviceprovider_id)->get();
            foreach ($serviceproviders as $provider) 
            {
              $type->price =  $provider->price;
            }        
        }
        if(count($sptostypes)>0)
        {
            return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all prices","payload"=> $sptostypes]);
        }
        else
        {
            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no Service type"]);
        }
      }
  }

  public function InstantBooking(Request $request)
  {
    $input = $request->all();
    $rules = array('id' => 'required','zipcode' => 'required','date' => 'required','time'=> 'required','servicetypes'=> 'required','address'=> 'required');
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {
      $user = User::find($request->id);
      if($user != null)
      {
        $zipcode = Zipcode::where('zipcode' ,$input['zipcode'])->first(); 
        if(!empty($zipcode))
        { 
           $role_id = Role::where('name','provider')->first();
           $providerlists_zipcodes = Useraddress::join('user_roles','user_roles.user_id','=','useraddresses.userId')
           ->where('useraddresses.zipCode',$zipcode->id)
           ->where('role_id', $role_id->id)
           ->get(['userId'])
           ->toArray();

          if(count($providerlists_zipcodes)> 0)
          {  
            $data = [];
            $filteredproviderlist = [];
            foreach($providerlists_zipcodes as $provider_match)
            { 
              $match_providers = User::where('id' ,$provider_match)
              ->where('status', 1)
              ->where('working_status', 1)
              ->whereNotNull('device_id')
              ->whereNotNull('device_type')
              ->get(['id'])
              ->toArray();
              foreach($match_providers as $providers)
              { 
               $servicetypes = $input['servicetypes'];
               $idsArr  = explode(',',$servicetypes); 
               $services = Servicetype::wherein('id', $idsArr)->get(['id','name']);  
                foreach($services as $service)
                {
                  $types = Sptostype::where('serviceprovider_id', $providers)->where('servicetype_id',$service->id)->get(['serviceprovider_id']);
                  foreach($types as $type)
                  {
                     $filteredproviderlist[] = $type;
                  }
                }
              }
            }
            foreach(array_unique($filteredproviderlist) as $data)
            {
                $response[] = $data->serviceprovider_id;
            }  
                  //----------servicename for notification data---------//
                  $service_name = $input['servicetypes'];
                  $service_name_aary  = explode(',',$service_name);
                  $nameArray = array();$dataArray = array();
                  foreach ($service_name_aary as $value) 
                  {
                    $service = Servicetype::where('id', $value)->first();     
                    array_push($nameArray, $service->name);
                    $service_prices = ServicePrice::where('servicetype_id',$service->id)->first();
                    array_push($dataArray,array("service_id"=>$service->id,"service_name"=>$service->name,"service_price"=>$service_prices->price));

                  }
                  $service_string = implode(' and ', $nameArray); 
                  //$service_price_res = implode(' , ', $priceArray);
              if(!empty($response))
              {
                  $insertbookingdetails = new InstantBooking;
                  $insertbookingdetails->cutomer_id       = $input['id'];
                  $insertbookingdetails->zipcode          = $input['zipcode'];
                  $insertbookingdetails->customer_address = $input['address'];
                  $insertbookingdetails->lat = $input['lat'];
                  $insertbookingdetails->long = $input['long'];
                  $insertbookingdetails->date             = $input['date'];
                  $insertbookingdetails->time             = $input['time'];
                  $insertbookingdetails->Services         = $input['servicetypes'];
                  $insertbookingdetails->save();
                  $insertbookingdetails->Services =  $dataArray;
                  //---------saving job loges-----------------------//

                foreach($response as $final)
                {
                  $user = User::where('id',$final)->first(); 
                  if($user->device_type == "A")
                  {
                      $url = "https://fcm.googleapis.com/fcm/send";
                      $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
                      $message = 
                      [ 
                        "to" => $user->device_id,
                        "data" => 
                            [
                              "title"        => "New Appointments",
                              "Service"      => $service_string,
                              "address"      => $insertbookingdetails->customer_address.",zipcode ".$insertbookingdetails->zipcode,
                              "Date"         => $insertbookingdetails->date ,
                              "Time"         => $insertbookingdetails->time,
                              "job_id"       => $insertbookingdetails->id,
                              "customer_id"  => $insertbookingdetails->cutomer_id,
                            ]
                      ];
                      $json = json_encode($message);
                      $headers = array(
                        'Content-Type: application/json',
                        'Authorization: key='. $serverKey
                      );
                      $ch = curl_init();
                      curl_setopt( $ch,CURLOPT_URL, $url);
                      curl_setopt( $ch,CURLOPT_POST, true );
                      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                      curl_setopt( $ch,CURLOPT_POSTFIELDS, $json);
                      //Send the request
                      $result = curl_exec($ch);
                      //Close request
                      if ($result === FALSE) 
                      {
                        die('FCM Send Error: ' . curl_error($ch)); 
                      }
                      else
                      {
                        curl_close($ch);   

                      }
                  }
                  else
                  { 
                    $url = "https://fcm.googleapis.com/fcm/send";
                    $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
                    $message = 
                      [ 
                        "to" => $user->device_id,
                        "priority" => 'high',
                        "sound" => 'default', 
                        "badge" => '1',
                        "notification" =>
                          [
                              "title" => "New Appointment",
                              "body" => $service_string,
                          ],
                        "data" => 
                          [ 
                            "notificationType" => "NewAppointment",
                            "Service"          => $service_string,
                            "address"          => $insertbookingdetails->customer_address.",zipcode ".$insertbookingdetails->zipcode,
                            "Date"             => $insertbookingdetails->date ,
                            "Time"             => $insertbookingdetails->time,
                            "job_id"           => $insertbookingdetails->id,
                            "customer_id"      => $insertbookingdetails->cutomer_id,
                          ]
                      ];
                      $json = json_encode($message);
                      $headers = array(
                        'Content-Type: application/json',
                        'Authorization: key='. $serverKey
                      );
                      $ch = curl_init();
                      curl_setopt( $ch,CURLOPT_URL, $url);
                      curl_setopt( $ch,CURLOPT_POST, true );
                      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                      curl_setopt( $ch,CURLOPT_POSTFIELDS, $json);
                      //Send the request
                      $result = curl_exec($ch);
                      //Close request
                      if ($result === FALSE) 
                      {
                        die('FCM Send Error: ' . curl_error($ch));
                      }
                      else
                      {
                        curl_close($ch); 
                      }
                  }
                  /*echo $user->id.'+'. $result;*/
               }
             
                 return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "Job Notification Sent to providers." ]); 
             }
             else
             {
                   return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "we do not have availability at this time."]);
             }
          }
          else
          {
              return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "We are unable to process your request right now, we will contact you later."]);
          }   
        }
        else
        {
           return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Sorry, we don’t cover your area yet, but will let you know when we do!"]);
        }
      }
      else
      {
        return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "User not found"]);
      }
    }
  }

 //---------------------Displaying Service Provider profile with notification(who accepted the job first)-----------------------------//

  public function showserviceproviderprofile(Request $request)
  {
    $input = $request->all();
    $rules = array('provider_id' => 'required',);
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {
      $providerdetails = User::where('id' ,$request->provider_id)->first();
      if(!empty($providerdetails))
      {  
        $provider_Bio =  Approved_Bio::where('serviceprovider_id' , $providerdetails->id)->first();
        if(!empty($provider_Bio))
        {
          $record = array(
            'provider_id' => $providerdetails->id,
            'provider_name' => $providerdetails->first_name,
            'Profile_Pic'  => secure_url('profile/'.$providerdetails->image),
            'StartTime' => $provider_Bio->starttime,
            'EndTime' => $provider_Bio->endtime,
            'Bio' => $provider_Bio->Bio,
          ); 
          return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "Service Provider Details", 'payload' => $record]); 
        }
        else
        {
           return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Sorry! Provider Details missing"]);
        }
      }
      else
      {
          return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Service Provider Details Not Available"]);
      }
    }
  }


  //--------Displying profile without Notification-------------//

  public function displayproviderprofile(Request $request)              
  {
    $input = $request->all();
    $rules = array('Job_id' => 'required',);
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {
      $accepted_or_not = Instant_schedule_job::where('job_id' ,$request->Job_id)->where('status' ,'0')->first();
      if(!empty($accepted_or_not))
      {
        $providerdetails = User::where('id' ,$accepted_or_not->provider_id)->first();
        if(!empty($providerdetails))
        {  
            $provider_Bio =  Approved_Bio::where('serviceprovider_id' , $providerdetails->id)->first();
            $record = array(
              'provider_id' => $providerdetails->id,
              'provider_name' => $providerdetails->first_name,
              'Profile_Pic'  => secure_url('profile/'.$providerdetails->image),
              'StartTime' => $provider_Bio->starttime,
              'EndTime' => $provider_Bio->endtime,
              'Bio' => $provider_Bio->Bio,
            ); 
            return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "Service Provider Details",'payLoad'=>$record]);   
        }
        else
        {
             return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Service Provider Details Not Available"]);
        } 
      }
      else
      {
        return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "job Not accepted Yet!"]);
      } 
    }
  }

  //--------Displying Upcoming Appointments -------------//

  public function ListOfCurrentJobsForCustomer(Request $request)
  {
    $input = $request->all();
    $lang  = $request->lang;
    $rules = array('Customer_id' => 'required',);
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
      return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {   
      $current_jobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
      ->where('instant_schedule_jobs.cutomer_id',$request->Customer_id)
      ->where('instant_schedule_jobs.status', '0')
      ->join('users', 'users.id', '=', 'instant_schedule_jobs.provider_id')           
      ->select( 'users.id as provider_id','users.first_name as Provider_name', 'users.image as Provider_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode',
        'instant_bookings.customer_address as customer_address','instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time')->get();

      foreach($current_jobs as $data)
      {
         $dataaaa = array();
         $idsss = explode(',',$data->Services_names);
         for($i=0;$i<count($idsss);$i++)
         {
            $getid =  $idsss[$i];
             if($lang == 'es')
             {
                $service = service_typesSpanish::where('servicetype_id',$getid)->first();
             }
             else
             {
                $service = Servicetype::where('id',$getid)->first();
             }
             array_push($dataaaa, $service->name);
         }
         if($lang == 'es')
         {
            $service_string = implode(' y ', $dataaaa);
         }
         else
         {
            $service_string = implode(' and ', $dataaaa);
         }
         $data->Services_names = $service_string;
         //$data->Provider_profile = URL::to('/').'/'.'profile/'.$data->Provider_profile;
$data->Provider_profile = secure_url("profile/".$data->Provider_profile);
      }

      if(count($current_jobs)> 0)
      {  
        return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'List Of Current Jobs.', 'payload' => $current_jobs]);
      }
      else
      {
        return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Sorry, No Current Jobs Available At This Time."]);
      }
    }
  }

  //--------Displying All Completed Appointments -------------//

  public function ListOfCompletedJobsForCustomer(Request $request)
  {
    $input = $request->all();
    $lang  = $request->lang;
    $rules = array('Customer_id' => 'required',);
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {   
      $CompletedJobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
        ->where('instant_schedule_jobs.cutomer_id',$request->Customer_id)
        ->where('instant_schedule_jobs.status', '1')
        ->join('users', 'users.id', '=', 'instant_schedule_jobs.provider_id')           
        ->select( 'users.id as provider_id','users.first_name as Provider_name','instant_bookings.id as job_id','users.image as Provider_profile','instant_bookings.Services as Services_names','instant_bookings.date as date')->get();

         /* NOT IN USE AS OF NOW : 'instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address','instant_bookings.time as time','instant_bookings.id as job_id','instant_bookings.customer_address as customer_address'*/

      foreach($CompletedJobs as $data)
      {
         $dataaaa = array();
         $idsss = explode(',',$data->Services_names);
         for($i=0;$i<count($idsss);$i++)
         {
            $getid =  $idsss[$i];
            if($lang == 'es')
            {
              $service = service_typesSpanish::where('servicetype_id',$getid)->first();
            }
            else
            {
                $service = Servicetype::where('id',$getid)->first();
            }
            array_push($dataaaa, $service->name);
         }
         if($lang == 'es')
         {
            $service_string = implode(' y ', $dataaaa);
         }
         else
         {
            $service_string = implode(' and ', $dataaaa);
         } 
         $data->Services_names = $service_string;
         $data->Provider_profile = secure_url("profile/".$data->Provider_profile);
         $NoofJobs = Instant_schedule_job::where('provider_id' , $data->provider_id)->where('status' , '1')->get();
         $data->NoOfJobsCompleted= count($NoofJobs);    //Total No of jobs Completed
         $review = ProviderReview::where('provider_id', $data->provider_id)->get();
         $totalreview  = count($review);      
        if($totalreview > 0)
        {
            $totalrateing = collect($review)->sum('review');
            $data->AverageRating = ($totalrateing / $totalreview);     //Average rating         
        }
        else
        {
          $data->AverageRating = 0;
        }
      }
      if(count($CompletedJobs)> 0)
      {  
          return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'List Of Completed Jobs.', 'payload' => $CompletedJobs]);
      }
      else
      {
        return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Sorry, No Completed Jobs Available At This Time."]);
      }
    }
  }

    //--------Displying All Canceled Appointments -------------//

  public function ListOfCanceledJobsForCustomer(Request $request)
  {
    $input = $request->all();
    $lang  = $request->lang;
    $rules = array('Customer_id' => 'required',);
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {   
        $CanceledJobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
          ->where('instant_schedule_jobs.cutomer_id',$request->Customer_id)
          ->where('instant_schedule_jobs.status', '3')
          ->join('users', 'users.id', '=', 'instant_schedule_jobs.provider_id')           
          ->select( 'users.id as provider_id','users.first_name as Provider_name', 'users.image as Provider_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address',
            'instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time'
          )->get();

        foreach($CanceledJobs as $data)
        {
           $dataaaa = array();
           $idsss = explode(',',$data->Services_names);
           for($i=0;$i<count($idsss);$i++)
           {
             $getid =  $idsss[$i];
             if($lang == 'es')
             {
               $service = service_typesSpanish::where('servicetype_id',$getid)->first();
             }
             else
             {
                $service = Servicetype::where('id',$getid)->first();
             }
             array_push($dataaaa, $service->name);
           }
           if($lang == 'es')
           {
             $service_string = implode(' y ', $dataaaa);
           }
           else
           {
             $service_string = implode(' and ', $dataaaa);
           } 
           $data->Services_names = $service_string;
           $data->Provider_profile = secure_url("profile/".$data->Provider_profile);
        }

        if(count($CanceledJobs)> 0)
        {  
            return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'List Of Canceled Jobs.', 'payload' => $CanceledJobs]);
        }
        else
        {
          return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Sorry, No Canceled Jobs Available At This Time."]);
        }
    }
  }

  //--------Cancel Current Job("Here job will Canceled by Customer")-------------//

  public function CanceljobByCustomer(Request $request)
  {
    $input = $request->all();
    $rules = array('job_id' => 'required','cutomer_id' => 'required',);
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {
      $CancelJob = Instant_schedule_job::where('job_id', $request->job_id)->where('cutomer_id' , $request->cutomer_id)->first();  
      $CancelJob->status = '2'; 
      if($CancelJob->update())
      {
        $customer = User::where('id' , $CancelJob->provider_id)->first();
        if($customer->device_type == "A")
        {
          $url = "https://fcm.googleapis.com/fcm/send";
          $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
          $message = 
            [ 
              "to" => $customer->device_id,
              "data" => 
                  [
                    "title"   => "Job Canceled By Provider",
                    "job_id"  => $CancelJob->job_id,     
                  ]
            ];
          $json = json_encode($message);
          $headers = array(
            'Content-Type: application/json',
            'Authorization: key='. $serverKey
          );
          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, $url);
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, $json);
          //Send the request
          $result = curl_exec($ch);
          //Close request
          if ($result === FALSE) 
          {
            die('FCM Send Error: ' . curl_error($ch)); 
          }
          else
          {
            curl_close($ch);   
          }
        }
        else
        { 
          $url = "https://fcm.googleapis.com/fcm/send";
          $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
          $message = 
            [ 
              "to" => $customer->device_id,
              "priority" => 'high',
              "sound" => 'default', 
              "badge" => '1',
              "notification" =>
                [
                    "title"   => "Job Canceled By Customer",
                    "job_id"  => $CancelJob->job_id,   
                ],
                "data" => 
                [ 
                    "notificationType" => "cancelledbycustomer",
                    "payload" =>[
                        "title"   => "Job Canceled By Customer",
                        "job_id"  => $CancelJob->job_id,  
                    ]
                ]
            ];
          $json = json_encode($message);
          $headers = array(
            'Content-Type: application/json',
            'Authorization: key='. $serverKey
          );
          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, $url);
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, $json);
          //Send the request
          $result = curl_exec($ch);
          //Close request
          if ($result === FALSE) 
          {
            die('FCM Send Error: ' . curl_error($ch));
          }
          else
          {
            curl_close($ch); 
          }
        }   
        return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Job cancelled Successfully']);
      }
      else
      {
         return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Something Went Wrong"]);
      }
    }
  }

  public function SpecialRequestToCleaner(Request $request)
  {
    $input = $request->all();
    $rules = array(
      'Job_id' => 'required',
      'customer_id' => 'required',
      'Provider_id' => 'required',
      'packing_instrustion'=> 'required',
      'special_Request'=> 'required');
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {
      $jobinstrutionandspecialrequest = new SpecialRequestToCleaner;
      $jobinstrutionandspecialrequest->Job_id               = $input['Job_id'];
      $jobinstrutionandspecialrequest->customer_id          = $input['customer_id'];
      $jobinstrutionandspecialrequest->Provider_id          = $input['Provider_id'];
      $jobinstrutionandspecialrequest->packing_instrustion  = $input['packing_instrustion'];
      $jobinstrutionandspecialrequest->special_Request      = $input['special_Request'];
      if($jobinstrutionandspecialrequest->save())
      {
          return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Special Request Added' ]);
      }
      else
      {
          return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Something Went Wrong"]);
      }
    }
  }

  public function providerReviews(Request $request)
  {
    $input = $request->all();
    $rules = array(
      'customer_id' => 'required',
      'job_id'      => 'required',
      'provider_id' => 'required',
      'review'      => 'required',
      'comment'     => 'required');
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {
      $providerreview = new ProviderReview;
      $providerreview->customer_id     = $input['customer_id'];
      $providerreview->job_id          = $input['job_id'];
      $providerreview->provider_id     = $input['provider_id'];
      $providerreview->review          = $input['review'];
      $providerreview->comment         = $input['comment'];
      if($providerreview->save())
      {
          return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Review Added' ]);
      }
      else
      {
          return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Something Went Wrong"]);
      }
    }
  }

  public function AllPreviousJobs(Request $request)
  {
    $input = $request->all();
    $lang  = $request->lang;
    $rules = array('Customer_id' => 'required',);
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {
     $Perviousjobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
      ->where('instant_schedule_jobs.cutomer_id',$request->Customer_id)
      ->where('instant_schedule_jobs.status', '1')
      ->join('users', 'users.id', '=', 'instant_schedule_jobs.provider_id')           
      ->select( 'users.id as provider_id', 'users.image as Provider_profile','instant_schedule_jobs.job_id as job_id','instant_bookings.Services as Services_names','instant_bookings.date as date')->get();

        foreach($Perviousjobs as $data)
        {
         $dataaaa = array();
         $idsss = explode(',',$data->Services_names);
         for($i=0;$i<count($idsss);$i++)
         {
           $getid =  $idsss[$i];
            if($lang == 'es')
             {
               $service = service_typesSpanish::where('servicetype_id',$getid)->first();
             }
             else
             {
                $service = Servicetype::where('id',$getid)->first();
             }
           array_push($dataaaa, $service->name);
         }
         if($lang == 'es')
           {
             $service_string = implode(' y ', $dataaaa);
           }
           else
           {
             $service_string = implode(' and ', $dataaaa);
           }  
         $data->Services_names = $service_string;
         $data->Provider_profile = secure_url("profile/".$data->Provider_profile);
         $review = ProviderReview::where('provider_id', $data->provider_id)->get();
         $totalreview  = count($review);      
        if($totalreview > 0)
        {
            $totalrateing = collect($review)->sum('review');
            $data->AverageRating = ($totalrateing / $totalreview);     //Average rating         
        }
        else
        {
          $data->AverageRating = 0;
        }
      }
      if(count($Perviousjobs)> 0)
      {  
          return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'List Of Previous Jobs.', 'payload' => $Perviousjobs]);
      }
      else
      {
        return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Sorry, No Previous Jobs Available At This Time."]);
      }
    }  
  }

  public function FullPreviousJobDetails(Request $request)
  {
    $input = $request->all();
    $lang  = $request->lang;
    $rules = array('job_id' => 'required','customer_id' => 'required','provider_id' => 'required');
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    { 
        $Perviousjobsdetails = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
        ->where('instant_schedule_jobs.provider_id',$request->provider_id)
        ->where('instant_schedule_jobs.job_id',$request->job_id)
        ->where('instant_schedule_jobs.cutomer_id',$request->customer_id)
        ->where('instant_schedule_jobs.status', '1')
        ->join('users', 'users.id', '=', 'instant_schedule_jobs.provider_id')           
        ->select('users.id as provider_id','users.first_name as Provider_name','instant_bookings.Services as Services_names','instant_bookings.zipcode as zipcode','instant_bookings.date as lastappointment','instant_bookings.customer_address as customer_address')->first(); 

       $dataaaa = array();
       $idsss = explode(',',$Perviousjobsdetails->Services_names);
       for($i=0;$i<count($idsss);$i++)
       {
         $getid =  $idsss[$i];
         if($lang == 'es')
         {
           $service = service_typesSpanish::where('servicetype_id',$getid)->first();
         }
         else
         {
            $service = Servicetype::where('id',$getid)->first();
         }
         array_push($dataaaa, $service->name);
       }
       if($lang == 'es')
       {
         $service_string = implode(' y ', $dataaaa);
       }
       else
       {
         $service_string = implode(' and ', $dataaaa);
       }  
       $Perviousjobsdetails->Services_names = $service_string;
       $review = ProviderReview::where('provider_id', $Perviousjobsdetails->provider_id)->get();
       $totalreview  = count($review);      
      if($totalreview > 0)
      {
         $totalrateing = collect($review)->sum('review');
         $Perviousjobsdetails->AverageRating = ($totalrateing / $totalreview);     //Average rating         
      }
      else
      {
         $Perviousjobsdetails->AverageRating = 0;
      }
      if(!empty($Perviousjobsdetails))
      {  
          return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Provider Details For Rebooking Previous job.', 'payload' => $Perviousjobsdetails]);
      }
      else
      {
        return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Something Went Wrong"]);
      }
    }
  }
    
 
  public function ReebookPreviousCleaner(Request $request)
  {
    $input = $request->all();
    $rules = array('job_id' => 'required','customer_id' => 'required','date' => 'required','time'=> 'required');
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
    }
    else 
    {  
      $previousdetails = InstantBooking::where('id' , $input['job_id'])->where('cutomer_id' , $input['customer_id'])->first();
      if(!empty($previousdetails))
      {   
          $provider_id = Instant_schedule_job::where('job_id' , $previousdetails->id)->first();
          $user = User::where('id',$provider_id->provider_id)->first(); 

          $service_name = $previousdetails->Services;
          $service_name_aary  = explode(',',$service_name);
          $nameArray = array();
          foreach ($service_name_aary as $value) 
          {
            $service = Servicetype::where('id', $value)->first();     
            array_push($nameArray, $service->name);
          }
          $service_string = implode(' and ', $nameArray); 

          if($user->device_type == "A")
          {
              $url = "https://fcm.googleapis.com/fcm/send";
              $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
              $message = 
              [ 
                "to" => $user->device_id,
                "data" => 
                  [
                    "title"        => "Rebooked Previous job",
                    "Service"      => $service_string,
                    "address"      => $previousdetails->customer_address.",zipcode ".$previousdetails->zipcode,
                    "Date"         => $input['date'] ,
                    "Time"         => $input['time'],
                    "job_id"       => $input['job_id'],
                    "customer_id"  => $previousdetails->cutomer_id,
                  ]
              ];
              $json = json_encode($message);
              $headers = array(
                'Content-Type: application/json',
                'Authorization: key='. $serverKey
              );
              $ch = curl_init();
              curl_setopt( $ch,CURLOPT_URL, $url);
              curl_setopt( $ch,CURLOPT_POST, true );
              curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
              curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
              curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
              curl_setopt( $ch,CURLOPT_POSTFIELDS, $json);
              //Send the request
              $result = curl_exec($ch);
              //Close request
              if ($result === FALSE) 
              {
                die('FCM Send Error: ' . curl_error($ch)); 
              }
              else
              {
                curl_close($ch); 
              }
          }
          else
          { 
            $url = "https://fcm.googleapis.com/fcm/send";
            $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
            $message = 
              [ 
                "to" => $user->device_id,
                "priority" => 'high',
                "sound" => 'default', 
                "badge" => '1',
                "notification" =>
                  [
                      "title" => "Rebooked Previous job",
                      "body" => $service_string,
                  ],
                "data" => 
                  [ 
			"notificationType" => "Rebooked Previous job",
                    "Service"      => $service_string,
                    "address"      => $previousdetails->customer_address.",zipcode ".$previousdetails->zipcode,
                    "Date"         => $input['date'] ,
                    "Time"         => $input['time'],
                    "job_id"       => $input['job_id'],
                    "customer_id"  => $previousdetails->cutomer_id,
                  ]
              ];
              $json = json_encode($message);
              $headers = array(
                'Content-Type: application/json',
                'Authorization: key='. $serverKey
              );
              $ch = curl_init();
              curl_setopt( $ch,CURLOPT_URL, $url);
              curl_setopt( $ch,CURLOPT_POST, true );
              curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
              curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
              curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
              curl_setopt( $ch,CURLOPT_POSTFIELDS, $json);
              //Send the request
              $result = curl_exec($ch);
              //Close request
              if ($result === FALSE) 
              {
                die('FCM Send Error: ' . curl_error($ch));
              }
              else
              {
                curl_close($ch); 
              }
          } 

          $insertbookingdetails = new InstantBooking;
          $insertbookingdetails->cutomer_id       = $previousdetails->cutomer_id;
          $insertbookingdetails->Parent_id        = $input['job_id'];
          $insertbookingdetails->zipcode          = $previousdetails->zipcode;
          $insertbookingdetails->customer_address = $previousdetails->customer_address;
          $insertbookingdetails->date             = $input['date'];
          $insertbookingdetails->time             = $input['time'];
          $insertbookingdetails->Services         = $previousdetails->Services; 
          $insertbookingdetails->lat         = $previousdetails->lat;
          $insertbookingdetails->long         = $previousdetails->long;
          if($insertbookingdetails->save())
          {
              return Response::json(['isSuccess' => true, 'isError' => false, 'message' => "Rebooking Details Saved"]);
          }
          else
          {
              return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Something Went Wrong"]);
          }
      }
      else
      {
          return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Something Went Wrong"]);
      }
    }
  }
}
