<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Auth;
use URL;
use File;
use Mail;
use Imageresize;
use Redirect;
use App\User;
use App\Role;
use App\Userrole;
use App\Service;
use App\Servicetype;
use App\Serviceprovider;
use App\Sptostype;
use App\ServicePrice;
use App\InstantBooking;
use App\Instant_schedule_job;
use App\ProviderReview;
use App\Rebookedjob;
use App\Useraddress;
use App\Zipcode;
class InstantBookingCantroller extends Controller
{ 
   public function OpenOpportunity()
   {
       $acceptedjobs = Instant_schedule_job::get(['job_id']);
       $job_ids = array();
       foreach ($acceptedjobs as $key => $value)
       {
         $job_ids[] = $value->job_id;
       }

      $OpenOpportunites = InstantBooking::whereNotIn('id' , $job_ids)->where('Parent_id' , '=' ,null)->orderBy('id','desc')->get();
      foreach($OpenOpportunites as $data)
      {
         $dataaaa = array();
         $idsss = explode(',',$data->Services);
         for($i=0;$i<count($idsss);$i++)
         {
           $getid =  $idsss[$i];
           $service = Servicetype::where('id',$getid)->first();
           array_push($dataaaa, $service->name);
         }
         $service_string = implode(' , ', $dataaaa); 
         $data->Services_names = $service_string;
         $customername = User::where('id' , $data->cutomer_id)->first();
         $data->cust_firstname= $customername->first_name;
         $data->cust_image= $customername->image; 
      }
      return view('admin.InstantBooking.openopportunity',compact('OpenOpportunites'));
    }  

    public function show_opportunity(Request $request)
    {
      $dataaaa = array();
      $opportunity = InstantBooking::find($request->id);
      $idsss = explode(',',$opportunity->Services);
      for($i=0;$i<count($idsss);$i++)
      {
       $getid =  $idsss[$i];
       $service = Servicetype::where('id',$getid)->first();
       array_push($dataaaa, $service->name);
      }
      $service_string = implode(' , ', $dataaaa);
      $opportunity->Services_names = $service_string;
      $customername = User::find($opportunity->cutomer_id);
      $opportunity->cust_firstname= $customername->first_name;
      $opportunity->cust_image= $customername->image;

      $role = Role::where('name','provider')->first();
      $providers = array();
      if($role != '')
      {
        $user_role = Userrole::where('role_id',$role->id)->get();
        if(!empty($user_role))
        {
          foreach($user_role as $user)
          {
            $users = User::find($user->user_id);
            if($users != '')
            {
              $user_address = Useraddress::where('userId',$users->id)->get();
              if(!empty($user_address))
              {
                foreach($user_address as $address)
                {
                  $zipcode = Zipcode::find($address->zipCode);
                  if($zipcode != '')
                  {
                    $provider_details = $users->id.".".$zipcode->zipcode. "|". $users->first_name." ".$users->last_name;
                    $provider_id = $users->id;
                  }
                }
                array_push($providers,$provider_details);
              }
            }
          }
        }
      }
      $opportunity->providers = $providers;
      return view('admin.InstantBooking.showopportunity',compact('opportunity'));
    }

    public function SendNotificationToProvider(Request $request)
    {
      $dataaaa = array();
      $opportunity = InstantBooking::find($request->id);
      $idsss = explode(',',$opportunity->Services);
      for($i=0;$i<count($idsss);$i++)
      {
       $getid =  $idsss[$i];
       $service = Servicetype::where('id',$getid)->first();
       array_push($dataaaa, $service->name);
      }
      $service_string = implode(' , ', $dataaaa);
      $opportunity->Services_names = $service_string;
      
      $notificatiotitle = 'Job Assigned By Admin';
      $notificatiobody  = 'Provider Notification';
      $notificationtype = "assignedByAdmin";
      $user = User::find($request->provider_id);

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
                  "Service"      => $opportunity->Services_names,
                  "address"      => $opportunity->customer_address.",zipcode ".$opportunity->zipcode,
                  "Date"         => $opportunity->date ,
                  "Time"         => $opportunity->time,
                  "job_id"       => $opportunity->id,
                  "customer_id"  => $opportunity->cutomer_id,
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
                  "title" => $notificatiotitle,
                  "body" => $opportunity->Services_names,
              ],
            "data" => 
              [ 
                "notificationType" => $notificationtype,
                "Service"          => $opportunity->Services_names,
                "address"          => $opportunity->customer_address.",zipcode ".$opportunity->zipcode,
                "Date"             => $opportunity->date ,
                "Time"             => $opportunity->time,
                "job_id"           => $opportunity->id,
                "customer_id"      => $opportunity->cutomer_id,
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
      return response()->json(['status'=>'1','url'=>route('OpenOpportunity')]);  
    }

    public function viewAllCurrentRunningJobs()
    {
      $current_jobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
            ->where('instant_schedule_jobs.status', '0')
            ->join('users', 'users.id', '=', 'instant_bookings.cutomer_id')
            ->join('service_types', 'service_types.id', '=', 'instant_bookings.Services')
            ->select('users.id as customer_id','users.first_name as customer_name','users.image as customer_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address','instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time','instant_schedule_jobs.provider_id as serviceprovider_id','instant_schedule_jobs.id as id')
            ->orderBy('id','desc')
            ->get();

      foreach($current_jobs as $data)
      {
         $dataaaa = array();
         $idsss = explode(',',$data->Services_names);
         for($i=0;$i<count($idsss);$i++)
         {
           $getid =  $idsss[$i];
           $service = Servicetype::where('id',$getid)->first();
           array_push($dataaaa, $service->name);
         }
         $service_string = implode(' , ', $dataaaa); 
         $data->Services_names = $service_string;
         $providername = User::where('id' , $data->serviceprovider_id)->first();
         $data->Provider_fistname = $providername->first_name;
       }
    	return view('admin.InstantBooking.currentJobList',compact('current_jobs'));
    }   


    public function cancelthisjob(Request $request)
    {
        $CancelJob = Instant_schedule_job::find($request->id);
        $CancelJob->status = '2';  
        if($CancelJob->update())
        {   
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function viewAllCompletedJobs()
    {
        $CompletedJobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
            ->where('instant_schedule_jobs.status', '1')
            ->join('users', 'users.id', '=', 'instant_bookings.cutomer_id')
            ->join('service_types', 'service_types.id', '=', 'instant_bookings.Services')
            ->select('users.id as customer_id','users.first_name as customer_name','users.image as customer_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address','instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time','instant_schedule_jobs.provider_id as serviceprovider_id','instant_schedule_jobs.id as id')
            ->orderBy('id','desc')
            ->get();

	      foreach($CompletedJobs as $data)
	      {
		         $dataaaa = array();
		         $idsss = explode(',',$data->Services_names);
		         for($i=0;$i<count($idsss);$i++)
		         {
		           $getid =  $idsss[$i];
		           $service = Servicetype::where('id',$getid)->first();
		           array_push($dataaaa, $service->name);
		         }
		         $service_string = implode(' , ', $dataaaa); 
		         $data->Services_names = $service_string;
		         $providername = User::where('id' , $data->serviceprovider_id)->first();
		         $data->Provider_fistname = $providername->first_name;
             $NoofJobs = Instant_schedule_job::where('provider_id' , $data->serviceprovider_id)->where('status' , '1')->get();
              if(count($NoofJobs) > 0)
              {
                $data->NoOfJobsCompleted= count($NoofJobs)." Jobs Done ";    //Total No of jobs Completed      
              }
              else
              {
                $data->NoOfJobsCompleted= "No Completed jobs";
              }
              $review = ProviderReview::where('provider_id', $data->serviceprovider_id)->get();
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
    	return view('admin.InstantBooking.completedJobsList',compact('CompletedJobs'));
    } 

    public function viewAllProviderCanceledJobs()
    {
        $P_CanceledJobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
            ->where('instant_schedule_jobs.status', '2')
            ->join('users', 'users.id', '=', 'instant_bookings.cutomer_id')
            ->join('service_types', 'service_types.id', '=', 'instant_bookings.Services')
            ->select('users.id as customer_id','users.first_name as customer_name','users.image as customer_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address','instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time','instant_schedule_jobs.provider_id as serviceprovider_id','instant_schedule_jobs.id as id')
            ->orderBy('id','desc')
            ->get();

	       foreach($P_CanceledJobs as $data)
	       {
		         $dataaaa = array();
		         $idsss = explode(',',$data->Services_names);
		         for($i=0;$i<count($idsss);$i++)
		         {
		           $getid =  $idsss[$i];
		           $service = Servicetype::where('id',$getid)->first();
		           array_push($dataaaa, $service->name);
		         }
		         $service_string = implode(' , ', $dataaaa); 
		         $data->Services_names = $service_string;
		         $providername = User::where('id' , $data->serviceprovider_id)->first();
		         $data->Provider_fistname = $providername->first_name;
	       }
    	return view('admin.InstantBooking.pro_cancelledJobList',compact('P_CanceledJobs'));
    } 

    public function viewAllCustomerCanceledJobs()
    {
        $C_CanceledJobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
            ->where('instant_schedule_jobs.status', '3')
            ->join('users', 'users.id', '=', 'instant_bookings.cutomer_id')
            ->join('service_types', 'service_types.id', '=', 'instant_bookings.Services')
            ->select('users.id as customer_id','users.first_name as customer_name','users.image as customer_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address','instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time','instant_schedule_jobs.provider_id as serviceprovider_id','instant_schedule_jobs.id as id')
            ->orderBy('id','desc')
            ->get();

	       foreach($C_CanceledJobs as $data)
	       {
		         $dataaaa = array();
		         $idsss = explode(',',$data->Services_names);
		         for($i=0;$i<count($idsss);$i++)
		         {
		           $getid =  $idsss[$i];
		           $service = Servicetype::where('id',$getid)->first();
		           array_push($dataaaa, $service->name);
		         }
		         $service_string = implode(' , ', $dataaaa); 
		         $data->Services_names = $service_string;
		         $providername = User::where('id' , $data->serviceprovider_id)->first();
		         $data->Provider_fistname = $providername->first_name;
	       }
    	return view('admin.InstantBooking.cust_cancelledJobList',compact('C_CanceledJobs'));
    } 

    public function Rebookedjobs()
    {    
        $Rebookedjobs = InstantBooking::join('instant_schedule_jobs','instant_schedule_jobs.job_id','=','instant_bookings.Parent_id')
            ->where('instant_schedule_jobs.status', '1')
            ->join('users', 'users.id', '=', 'instant_bookings.cutomer_id')
            ->join('service_types', 'service_types.id', '=', 'instant_bookings.Services')
            ->select('users.id as customer_id','users.first_name as customer_name','users.image as customer_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address','instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time','instant_schedule_jobs.provider_id as serviceprovider_id','instant_schedule_jobs.id as id')
            ->orderBy('id','desc')
            ->get();

         foreach($Rebookedjobs as $data)
         {
             $dataaaa = array();
             $idsss = explode(',',$data->Services_names);
             for($i=0;$i<count($idsss);$i++)
             {
               $getid =  $idsss[$i];
               $service = Servicetype::where('id',$getid)->first();
               array_push($dataaaa, $service->name);
             }
             $service_string = implode(' , ', $dataaaa); 
             $data->Services_names = $service_string;
             $providername = User::where('id' , $data->serviceprovider_id)->first();
             $data->Provider_fistname = $providername->first_name;
             $data->Provider_profilepic = $providername->image;
             $data->Provider_mobileno = $providername->mobile;
             $NoofJobs = Instant_schedule_job::where('provider_id' , $data->serviceprovider_id)->where('status' , '1')->get();
              if(count($NoofJobs) > 0)
              {
                $data->NoOfJobsCompleted= count($NoofJobs)." Jobs Done ";    //Total No of jobs Completed      
              }
              else
              {
                $data->NoOfJobsCompleted= "No Completed jobs";
              }
              $review = ProviderReview::where('provider_id', $data->serviceprovider_id)->get();
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
        return view('admin.ScheduledBooking.Rebookedjobs',compact('Rebookedjobs'));
    } 

    public function get_completed_jobs_by_city()
    {
      $jobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
            ->where('instant_schedule_jobs.status','1')
            ->select('instant_bookings.zipcode as zipcode')
            ->get();

      foreach($jobs as $job)
      {
        $zipcode = \App\Zipcode::whereZipcode($job->zipcode)->first();
        $job->city_name = $zipcode->city->name;
      }

      $collections = collect($jobs);
      $results = $collections->unique('zipcode');
      $results->values()->all();

      return view('admin.InstantBooking.completed_jobs_by_city',compact('results'));
    }

    public function Completed_jobs_by_id($id)
    {
      $jobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
            ->where('instant_schedule_jobs.status', '1')
            ->where('instant_bookings.zipcode',$id)
            ->join('users', 'users.id', '=', 'instant_bookings.cutomer_id')
            ->join('service_types', 'service_types.id', '=', 'instant_bookings.Services')
            ->select('users.id as customer_id','users.first_name as customer_name','users.image as customer_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address','instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time','instant_schedule_jobs.provider_id as serviceprovider_id','instant_schedule_jobs.id as id')
            ->orderBy('id','desc')
            ->get();
      foreach($jobs as $data)
      {
       $dataaaa = array();
       $idsss = explode(',',$data->Services_names);
       for($i=0;$i<count($idsss);$i++)
       {
         $getid =  $idsss[$i];
         $service = Servicetype::where('id',$getid)->first();
         array_push($dataaaa, $service->name);
       }
       $service_string = implode(' , ', $dataaaa); 
       $data->Services_names = $service_string;
       $providername = User::where('id' , $data->serviceprovider_id)->first();
       $data->Provider_fistname = $providername->first_name;
       $NoofJobs = Instant_schedule_job::where('provider_id' , $data->serviceprovider_id)->where('status' , '1')->get();
        if(count($NoofJobs) > 0)
        {
          $data->NoOfJobsCompleted= count($NoofJobs)." Jobs Done ";    //Total No of jobs Completed      
        }
        else
        {
          $data->NoOfJobsCompleted= "No Completed jobs";
        }
        $review = ProviderReview::where('provider_id', $data->serviceprovider_id)->get();
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
      return view('admin.InstantBooking.completed_jobs_by_id',compact('jobs'));
    }

    public function unfilled_jobs()
    {
      $states = []; $cities = [];
      $final_states = []; $final_cities = [];
      $acceptedjobs = Instant_schedule_job::get(['job_id']);
      $job_ids = array();
      foreach ($acceptedjobs as $key => $value)
      {
        $job_ids[] = $value->job_id;
      }
      $OpenOpportunites = InstantBooking::whereNotIn('id',$job_ids)->where('Parent_id','=',null)->orderBy('id','desc')->get()->pluck('zipcode');
      $countries = \App\Country::get();
      $zipcodes = \App\Zipcode::with('city')->whereIn('zipcode',$OpenOpportunites)->get();
      foreach($zipcodes as $zipcode)
      {
        $state = $zipcode->city->state->name;
        $city = $zipcode->city->name;
        array_push($states,$state);
        array_push($cities,$city);
      }

      return view('admin.InstantBooking.unfilled_jobs',compact('countries','states','final_states','final_cities'));
    }

    public function get_unfilled_jobs_city($id)
    {
      $cities = [];
      $state = \App\State::with('cities')->find($id);
      $acceptedjobs = Instant_schedule_job::get(['job_id']);
      $job_ids = array();
      foreach ($acceptedjobs as $key => $value)
      {
        $job_ids[] = $value->job_id;
      }
      $OpenOpportunites = InstantBooking::whereNotIn('id',$job_ids)->where('Parent_id','=',null)->orderBy('id','desc')->get()->pluck('zipcode');
      $countries = \App\Country::get();
      $zipcodes = \App\Zipcode::with('city')->whereIn('zipcode',$OpenOpportunites)->get();
      foreach($zipcodes as $zipcode)
      {
        $city = $zipcode->city->name;
        array_push($cities,$city);
      }
      $html = '';
      foreach($state->cities as $city)
      {
        if(in_array($city->name,$cities))
        {
          $html.='<div class="col-md-3"><div class="cnty-btn active"><span class="country-name-text">'.$city->name.'</span><span class="circle-right "></span></div></div>';
        }
        else
        {
          $html.='<div class="col-md-3"><div class="cnty-btn"><span class="country-name-text">'.$city->name.'</span><span class="circle-right "></span></div></div>';
        }
      }
      return response()->json($html);
    }

} 
