<?php

namespace App\Http\Controllers\supervisior;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProviderController extends Controller
{
	public function __construct()
    {
        $this->middleware('supervisior');
    }

    public function dashboard()
    {
    	$providers = \App\User::join('user_roles','users.id','=','user_roles.user_id')
      	->join('roles','roles.id','=','user_roles.role_id')
      	->whereHas('roles', function ($query){ $query->where('name','provider'); })
      	->select('users.*','roles.name as role_name')->orderBy('id','desc')->count();
      	$approved_profiles = \App\ProviderProfile::whereStatus(1)->count();
      	$declined_profiles = \App\ProviderProfile::whereStatus(0)->count();
      	$bios = \App\Providerbio::whereStatus(0)->count();
    	return view('supervisor.dashboard',compact('providers','approved_profiles','declined_profiles','bios'));
    }
    
    public function employee()
    {
    	$employees = \App\User::join('user_roles','users.id','=','user_roles.user_id')
      	->join('roles','roles.id','=','user_roles.role_id')
      	->whereHas('roles', function ($query){ $query->where('name','provider'); })
      	->select('users.*','roles.name as role_name')->orderBy('id','desc')->get();
      	return view('supervisor.employee',compact('employees'));
    }

    public function employees_permission(Request $request)
    {
    	$employee = \App\User::find($request->id);
    	$employee->status = $request->status;
    	if($employee->save())
    	{
    		return response()->json(['status'=>true]);
    	}
    	else
    	{
    		return response()->json(['status'=>false]);
    	}
    }

    public function send_notic_to_all_customers(Request $request)
    {
    	if($request->isMethod('get'))
    	{
    		$users = \App\User::join('user_roles','users.id','=','user_roles.user_id')
	      	->join('roles','roles.id','=','user_roles.role_id')
	      	->whereHas('roles', function ($query){ $query->where('name','customer'); })
	      	->select('users.first_name','users.last_name','users.id')->get();
    		return view('supervisor.notification',compact('users'));
    	}
    	if($request->isMethod('post'))
    	{
    		$users = \App\User::whereIn('id',$request->user_id)->get();
	        if(count($users)>0)
	        {
	        	foreach($users as $user)
	          {
	            if($user->device_type == "A")
	            {
	              $url = "https://fcm.googleapis.com/fcm/send";
	              $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
	              $message = 
	              [ 
	                "to" => $user->device_id,
	                "data" => 
	                    [
	                      "title"     =>  "send notification to customer",
	                      "body"      =>  "send notification to customer",
	                      "notificationType" => "send notification to customer"
	                    ],
	                
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
	                    "title" => "send notification to customer",
	                    "body"  => "send notification to customer",
	                  ],
	                  "data"=>["notificationType" => "send notification to customer"]
	                  
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
	          }
	          return back()->with('success','You has been successfully sent notification to customers.');
	        }
	        else
	        {
	            return "error";
	        }
    	}
    }

    public function approve_pictures(Request $request)
    {
    	if($request->isMethod('get'))
    	{
    		$profiles = \App\ProviderProfile::whereStatus(0)->orWhere('status',1)->get();
    		return view('supervisor.approve_pic',compact('profiles'));
    	}
    	if($request->isMethod('post'))
    	{
    		$profile = \App\ProviderProfile::find($request->id);
    		$profile->status = $request->status;
    		if($profile->save())
    		{
    			return response()->json(['status'=>true]);
	    	}
	    	else
	    	{
	    		return response()->json(['status'=>false]);
	    	}
    	}
    }

    public function approve_bio(Request $request)
    {
    	if($request->isMethod('get'))
    	{
    		$bios = \App\Providerbio::get();
    		return view('supervisor.approve_bio',compact('bios'));
    	}
    	if($request->isMethod('post'))
    	{
    		$profile = \App\Providerbio::find($request->id);
    		$profile->status = $request->status;
    		if($profile->save())
    		{
    			return response()->json(['status'=>true]);
	    	}
	    	else
	    	{
	    		return response()->json(['status'=>false]);
	    	}
    	}
    }

    public function provider_create(Request $request)
    {
    	if($request->isMethod('get'))
	    {
	      $countries = \App\Country::get();
	      $roles = \App\Role::where('name','!=','admin')->get();
	      return view('supervisor.provider_create',compact('countries','roles'));
	    }
	    if($request->isMethod('post'))
	    {
	    	$this->validate($request,[
	    		'email'=>"required|email|unique:users"
	    	]);
	      $user = new \App\User;
	      $user->first_name = $request->first_name;
	      $user->last_name = $request->last_name;
	      $user->email = $request->email;
	      $user->password = \Hash::make($request->password);
	      $user->mobile = $request->mobile;
	      $user->status = 0;
	      $user->working_status = 0;
	      if($user->save())
	      {
	        $role_user = new \App\Userrole;
	        $role_user->user_id = $user->id;
	        $role_user->role_id = $request->role;
	        $role_user->save();

	        $user_address = new \App\Useraddress;
	        $user_address->userId = $user->id;
	        $user_address->country = $request->country_id;
	        $user_address->state = $request->state_id;
	        $user_address->city = $request->city_id;
	        $user_address->zipCode = $request->zipcode_id;
	        $user_address->address = $request->customer_address;
	        $user->save();

	        return redirect()->back()->with(['info'=>'success','message'=>'New Profile has been successfully created!.']);
	      }
	      else
	      {
	        return redirect()->back()->with(['info'=>'danger','message'=>'New Profile has not been successfully created!.']);
	      }
	  	}
    }

    public function ban_ipaddress(Request $request)
    {
    	if($request->isMethod('get'))
    	{
    		$users = \App\User::join('user_roles','users.id','=','user_roles.user_id')
	      	->join('roles','roles.id','=','user_roles.role_id')
	      	->whereHas('roles', function ($query){ $query->where('name','customer'); })
	      	->select('users.*')->orderBy('id','desc')->get();
	      	return view('supervisor.ban_ip',compact('users'));
    	}
    	if($request->isMethod('post'))
    	{
    		$user = \App\User::find($request->id);
    		$user->ban_ip = $request->status;
    		if($user->save())
    		{
    			return response()->json(['status'=>true]);
	    	}
	    	else
	    	{
	    		return response()->json(['status'=>false]);
	    	}
    	}
    }
    

    public function declined_pictures()
    {
    	$profiles = \App\ProviderProfile::whereStatus(2)->get();
    	return view('supervisor.declined_pic',compact('profiles'));
    }
	
    public function alter_cleaner_info(Request $request)
    {
    	if($request->isMethod('get'))
    	{
    		$countries = \App\Country::get();
    		$users = \App\User::join('user_roles','users.id','=','user_roles.user_id')
	      	->join('roles','roles.id','=','user_roles.role_id')
	      	->whereHas('roles', function ($query){ $query->where('name','customer'); })
	      	->select('users.*')->orderBy('id','desc')->get();
	      	return view('supervisor.alter_cleaner_info',compact('users','countries'));
    	}
    	if($request->isMethod('post'))
    	{
			//return $request->all();
    		$user = \App\User::find($request->id);
			if(!is_null($user))
			{
				$address = \App\Useraddress::where('userId',$user->id)->first();
				$address->country = $request->country_id;
				$address->address = $request->address;
				$address->state = $request->state_id;
				$address->city = $request->city_id;
				$address->zipCode = $request->zipcode_id;
				if($address->save())
				{
					return redirect()->route('supervisior::alter_provider_info')->with(['status'=>true,'message'=>'User information has been successfully updated.']);
				}
				else
				{
					return redirect()->route('supervisior::alter_provider_info')->with(['status'=>false,'message'=>'Something error while updating user information']);
				}
			}	
			else
			{
				alert('user not found');
			}
    	}
    }

    public function unfilled_jobs()
  {
    $countries = \App\Country::get();
    return view('supervisor.InstantBooking.unfilled_jobs',compact('countries'));
  }

    public function get_unfilled_jobs_state($id)
    {
      $html = '';
      $country = \App\Country::find($id);
      if(strtolower($country->name) == "europe")
      {
        $countries = \App\Country::whereParentId($id)->get();
        foreach($countries as $country)
        {
          $html.='<div class="col-md-3" onclick="get_state_list('.$country->id.')"><div class="cnty-btn active"><span class="country-name-text">'.$country->name.'</span><span class="circle-right "></span></div></div>';
        }
        return response()->json(['data'=>$html]);
      }
      else
      {
        $cities = []; $new_states = [];
        $states = \App\State::whereCountryId($id)->get();
        $acceptedjobs = \App\Instant_schedule_job::get(['job_id']);
        $job_ids = array();
        foreach ($acceptedjobs as $key => $value)
        {
          $job_ids[] = $value->job_id;
        }
        $OpenOpportunites = \App\InstantBooking::whereNotIn('id',$job_ids)->where('Parent_id','=',null)->orderBy('id','desc')->get()->pluck('zipcode');
        $zipcodes = \App\Zipcode::with('city')->whereIn('zipcode',$OpenOpportunites)->get();
        foreach($zipcodes as $zipcode)
        {
          $city = $zipcode->city->name;
          $state = $zipcode->city->state->name;
          array_push($cities,$city);
          array_push($new_states,$state);
        }
       
        if(count($states)>0)
        {
          foreach($states as $state)
          {
            if(in_array($state->name,$new_states))
            {
              $html.='<div class="col-md-3" onclick="states('.$state->id.')"><div class="cnty-btn active"><span class="country-name-text">'.$state->name.'</span><span class="circle-right "></span></div></div>';
            }
            else
            {
              $html.='<div class="col-md-3" onclick="states('.$state->id.')"><div class="cnty-btn"><span class="country-name-text">'.$state->name.'</span><span class="circle-right "></span></div></div>';
            }
          }
        }
        else
        {
          $html.='<div class="col-md-3"><div class="cnty-btn"><span class="country-name-text">No list found.</span></div></div>';
        }   
        return $html;
      }
    }

    public function get_unfilled_jobs_city($id)
    {
      $cities = [];
      $state = \App\State::with('cities')->find($id);
      $acceptedjobs = \App\Instant_schedule_job::get(['job_id']);
      $job_ids = array();
      foreach ($acceptedjobs as $key => $value)
      {
        $job_ids[] = $value->job_id;
      }
      $OpenOpportunites = \App\InstantBooking::whereNotIn('id',$job_ids)->where('Parent_id','=',null)->orderBy('id','desc')->get()->pluck('zipcode');
      $zipcodes = \App\Zipcode::with('city')->whereIn('zipcode',$OpenOpportunites)->get();
      foreach($zipcodes as $zipcode)
      {
        $city = $zipcode->city->name;
        array_push($cities,$city);
      }
      $html = '';
      if(count($state->cities)>0)
      {
        foreach($state->cities as $city)
        {
          if(in_array($city->name,$cities))
          {
            $html.='<div class="col-md-3" onclick="get_jobs_lists('.$city->id.')"><div class="cnty-btn active"><span class="country-name-text">'.$city->name.'</span><span class="circle-right "></span></div></div>';
          }
          else
          {
            $html.='<div class="col-md-3"><div class="cnty-btn"><span class="country-name-text">'.$city->name.'</span><span class="circle-right "></span></div></div>';
          }
        }
      }
      else
      {
        $html.='<div class="col-md-3"><div class="cnty-btn"><span class="country-name-text">No list found.</span></div></div>';
      }
      return response()->json($html);
    }

    public function get_unfilled_jobs($id)
    {
      $zipcode = \App\Zipcode::whereCityId($id)->get()->pluck('zipcode');
      $acceptedjobs = \App\Instant_schedule_job::get(['job_id']);
      $job_ids = array();
      foreach ($acceptedjobs as $key => $value)
      {
        $job_ids[] = $value->job_id;
      }
      $html = "";
      $OpenOpportunites = \App\InstantBooking::whereNotIn('id',$job_ids)->where('Parent_id','=',null)->whereIn('zipcode',$zipcode)->orderBy('id','desc')->get();
      if(count($OpenOpportunites)>0)
      {
        foreach($OpenOpportunites as $data)
        {
           $dataaaa = array();
           $idsss = explode(',',$data->Services);
           for($i=0;$i<count($idsss);$i++)
           {
             $getid =  $idsss[$i];
             $service = \App\Servicetype::where('id',$getid)->first();
             array_push($dataaaa, $service->name);
           }
           $service_string = implode(' , ', $dataaaa); 
           $data->Services_names = $service_string;
           $customername = \App\User::where('id' , $data->cutomer_id)->first();
           $data->cust_firstname= $customername->first_name;
           $data->cust_image= $customername->image; 
           $html.="<tr><td style='curser:pointer' onclick='get_job_list(".$data->zipcode.",".$data.")'>".$data->id."</td><td>".$data->cust_firstname."</td><td>".$data->customer_address."</td><td>".$data->zipcode."</td><td>".$data->date."</td><td>".$data->time."</td><td>".$data->Services_names."</td></tr>";
        }
      }
      else
      {
        $html.="No list found.";
      }
      return $html;
    }

    public function get_provider_zipcode(Request $request)
    {
      $data = $request->data;
      $active_users = [];$unaviable_users = [];$nearby = [];
      $zipcode_id = \App\Zipcode::whereZipcode($request->data['zipcode'])->first();
      if($zipcode_id != null)
      {
        if($zipcode_id->near_by != null)
        {
          $array = explode(',',$zipcode_id->near_by);
          $nearby_id = \App\Zipcode::whereIn('zipcode',$array)->get();
          if(count($nearby_id)>0)
          {
            foreach($nearby_id as $ids)
            {
              $providers = \App\Useraddress::where('zipCode',$ids->id)->get();
              if(count($providers)>0)
              {
                foreach($providers as $provider)
                {
                  $user = \App\User::find($provider->userId);
                  if($user != null)
                  {
                    $NoofJobs = \App\Instant_schedule_job::where('provider_id' , $user->id)->where('status' , '1')->get();
                    if(count($NoofJobs) > 0)
                    {
                      $user->NoOfJobsCompleted= count($NoofJobs)." Jobs Done "; 
                      
                      if(!is_null($provider->lat) && !is_null($provider->long)):
                        foreach($NoofJobs as $job):
                          $get_lat_longs = \App\InstantBooking::find($job->job_id);
                          if(!is_null($get_lat_longs)):
                            $lat1 = $provider->lat;
                            $lat2 = $get_lat_longs->lat;
                            $lon1 = $provider->long;
                            $lon2 = $get_lat_longs->long;
                            $theta = $lon1 - $lon2;
                            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                            $dist = acos($dist);
                            $dist = rad2deg($dist);
                            $miles = $dist * 60 * 1.1515;
                            $distance = $miles * 1.609344;
                            $user->distance = $distance;
                          else:
                            $user->distance = 0;
                          endif;
                        endforeach;
                        else:
                          $user->distance = 0;
                      endif;
                    }
                    else
                    {
                      $user->NoOfJobsCompleted= "No Completed jobs";
                      $user->distance = "0";
                    }
                    $review = \App\ProviderReview::where('provider_id', $user->id)->get();     
                    $totalreview = count($review);
                    if($totalreview > 0)
                    {
                      $totalrateing = collect($review)->sum('review');
                      $user->AverageRating = ($totalrateing / $totalreview);
                      $score = 50-(1*10)+(10+$user->AverageRating);
                      $user->score = $score;    
                    }
                    else
                    {
                      $user->AverageRating = 0;
                      $user->score = 0; 
                    }

                    array_push($nearby,$user);
                  }
                }
              }
            }
          }
          
        }
        $providers = \App\Useraddress::where('zipCode',$zipcode_id->id)->get();
        if(count($providers)>0)
        {
          foreach($providers as $provider)
          {
            $user = \App\User::find($provider->userId);
            if($user != null)
            {
              $NoofJobs = \App\Instant_schedule_job::where('provider_id' , $user->id)->where('status' , '1')->get();
              if(count($NoofJobs) > 0)
              {
                $user->NoOfJobsCompleted= count($NoofJobs)." Jobs Done "; 
                if(!is_null($provider->lat) && !is_null($provider->long)):
                  foreach($NoofJobs as $job):
                    $get_lat_longs = \App\InstantBooking::find($job->job_id);
                    if(!is_null($get_lat_longs)):
                      $lat1 = $provider->lat;
                      $lat2 = $get_lat_longs->lat;
                      $lon1 = $provider->long;
                      $lon2 = $get_lat_longs->long;
                      $theta = $lon1 - $lon2;
                      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                      $dist = acos($dist);
                      $dist = rad2deg($dist);
                      $miles = $dist * 60 * 1.1515;
                      $distance = $miles * 1.609344;
                      $user->distance = $distance;
                    else:
                      $user->distance = 0;
                    endif;
                  endforeach;
                  else:
                    $user->distance = 0;
                endif;
              }
              else
              {
                $user->NoOfJobsCompleted= "No Completed jobs";
                $user->distance = "0";
              }

              $review = \App\ProviderReview::where('provider_id', $user->id)->get();     
              $totalreview = count($review);
              if($totalreview > 0)
              {
                $totalrateing = collect($review)->sum('review');
                $user->AverageRating = ($totalrateing / $totalreview);
                $score = 50-(1*10)+(10+$user->AverageRating);
                $user->score = $score;     
              }
              else
              {
                $user->AverageRating = 0;
                $user->score = 0;    
              }
              if($user->working_status == 1)
              {
                array_push($active_users,$user);
              }
              if($user->working_status == 0)
              {
                array_push($unaviable_users,$user);
              }
            }
          }
        }
        
      }
      $view = view('supervisor.InstantBooking.get_provider_list',compact('active_users','unaviable_users','nearby','data'));
      return $view;
    }

    public function viewAllCurrentRunningJobs()
    {
      $current_jobs = \App\Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
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
           $service = \App\Servicetype::where('id',$getid)->first();
           array_push($dataaaa, $service->name);
         }
         $service_string = implode(' , ', $dataaaa); 
         $data->Services_names = $service_string;
         $providername = \App\User::where('id' , $data->serviceprovider_id)->first();
         $data->Provider_fistname = $providername->first_name;
       }
      return view('supervisor.InstantBooking.currentJobList',compact('current_jobs'));
    }   


    public function cancelthisjob(Request $request)
    {
        $CancelJob = \App\Instant_schedule_job::find($request->id);
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
        $CompletedJobs = \App\Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
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
               $service = \App\Servicetype::where('id',$getid)->first();
               array_push($dataaaa, $service->name);
             }
             $service_string = implode(' , ', $dataaaa); 
             $data->Services_names = $service_string;
             $providername = \App\User::where('id' , $data->serviceprovider_id)->first();
             $data->Provider_fistname = $providername->first_name;
             $NoofJobs = \App\Instant_schedule_job::where('provider_id' , $data->serviceprovider_id)->where('status' , '1')->get();
              if(count($NoofJobs) > 0)
              {
                $data->NoOfJobsCompleted= count($NoofJobs)." Jobs Done ";    //Total No of jobs Completed      
              }
              else
              {
                $data->NoOfJobsCompleted= "No Completed jobs";
              }
              $review = \App\ProviderReview::where('provider_id', $data->serviceprovider_id)->get();
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
      return view('supervisor.InstantBooking.completedJobsList',compact('CompletedJobs'));
    } 

    public function viewAllProviderCanceledJobs()
    {
        $P_CanceledJobs = \App\Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
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
               $service = \App\Servicetype::where('id',$getid)->first();
               array_push($dataaaa, $service->name);
             }
             $service_string = implode(' , ', $dataaaa); 
             $data->Services_names = $service_string;
             $providername = \App\User::where('id' , $data->serviceprovider_id)->first();
             $data->Provider_fistname = $providername->first_name;
         }
      return view('supervisor.InstantBooking.pro_cancelledJobList',compact('P_CanceledJobs'));
    } 

    public function viewAllCustomerCanceledJobs()
    {
        $C_CanceledJobs = \App\Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
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
               $service = \App\Servicetype::where('id',$getid)->first();
               array_push($dataaaa, $service->name);
             }
             $service_string = implode(' , ', $dataaaa); 
             $data->Services_names = $service_string;
             $providername = \App\User::where('id' , $data->serviceprovider_id)->first();
             $data->Provider_fistname = $providername->first_name;
         }
      return view('supervisor.InstantBooking.cust_cancelledJobList',compact('C_CanceledJobs'));
    } 

  public function ForgotCheckout()
  {
      $results = \App\JObCheckinOut::join('instant_bookings','j_ob_checkin_outs.job_id','=','instant_bookings.id')
      ->join('instant_schedule_jobs','instant_bookings.id','=','instant_schedule_jobs.job_id')
      ->select('instant_schedule_jobs.job_id','instant_schedule_jobs.provider_id','instant_schedule_jobs.status','j_ob_checkin_outs.id as job_checkin_out_id','j_ob_checkin_outs.checkIn','j_ob_checkin_outs.checkOut','instant_bookings.*')
      //->where('status','=','0')
      ->where('checkOut',Null)
      ->get();
      foreach($results as $result)
      {
        $service_id = explode(',',$result->Services);
        $services = \App\Servicetype::whereIn('id',$service_id)->pluck('name');
        $result->Services = $services;
      }

      //return $results;
      return view('supervisor.InstantBooking.forgot_check_out',compact('results'));
  }
  public function block()
  {
      $blocked_users = \App\Block::join('users','blocks.user_id','=','users.id')
      ->join('user_roles','users.id','=','user_roles.user_id')
      ->join('roles','roles.id','=','user_roles.role_id')
      ->select('users.*','blocks.status as block_status','blocks.time','roles.name as role_name','blocks.id as block_id')
      ->where('blocks.time','=',Null)
      ->get();

      $blocks = \App\Block::where('time','=',Null)->get()->pluck('user_id');
      $users = \App\User::join('user_roles','users.id','=','user_roles.user_id')
      ->join('roles','roles.id','=','user_roles.role_id')
      ->whereHas('roles', function ($query){ $query->whereIn('name',['customer','provider']); })
      ->whereNotIn('users.id',$blocks)
      ->select('users.*','roles.name as role_name')->distinct('users.id')
      ->get();

      return view('supervisor.block',compact('users','blocked_users'));
  } 

  public function add_block(Request $request)
  {
    $validator = \Validator::make($request->all(),
    [
      'time'  => 'date_format:H:i'
    ]);
    if ($validator->fails())
    {
     // return Response::json(['errors' => $validator->errors()]);
      return back()->with(['errors' => $validator->errors()]);
    }
    else
    {
      \App\Block::create($request->all());
      return back();
    }
  }

  public function unblock_list(Request $request)
  {
    $block = \App\Block::find($request->id);
    if($block->delete())
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }

  public function workingdays()
    {   
    	$Workingdays = \App\WorkingDays::all();   
    	return view('supervisor.ScheduledBooking.Workingdays',compact('Workingdays'));
    }  
    public function addworkingday(Request $request)
    {
        $validator = \Validator::make($request->all(),
        [
            'day'=>'required|unique:working_days',
        ]);
        if ($validator->fails())
        {
           return Response::json(['errors' => $validator->errors()]);
        }
        else
        {
            $workingday = new \App\WorkingDays;
            $workingday->day = $request->day;
            $workingday->status = 0;
            if($workingday->save())
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }       
    }
    
    public function deleteWorkingday(Request $request)
    {
        $workingDay = \App\WorkingDays::find($request->id);
        if($workingDay->delete())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function DailyScheduledJobs()
    {   
    	$Dailyjobs = \App\DailyScheduledJobs::join('users','users.id','=','daily_scheduled_jobs.customer_id')
        ->select('users.id as customer_id','users.first_name as customer_name','daily_scheduled_jobs.Zipcode as Zipcode','daily_scheduled_jobs.daily_time as daily_time','daily_scheduled_jobs.customer_address as customer_address','daily_scheduled_jobs.services as services','daily_scheduled_jobs.id as Job_id')
        ->get();

          foreach($Dailyjobs as $data)
          {
             $dataaaa = array();
             $idsss = explode(',',$data->services);
             for($i=0;$i<count($idsss);$i++)
             {
               $getid =  $idsss[$i];
               $service = \App\Servicetype::where('id',$getid)->first();
               array_push($dataaaa, $service->name);
             }
             $service_string = implode(' and ', $dataaaa); 
             $data->services = $service_string; 
          }
    	return view('supervisor.ScheduledBooking.Dailyjobs',compact('Dailyjobs'));
    } 

    public function WeeklyScheduledJobs()
    {   
        $Weeklyjobs = \App\WeeklyScheduledJobs::join('users','users.id','=','weekly_scheduled_jobs.customer_id')
        ->select('users.id as customer_id','users.first_name as customer_name','weekly_scheduled_jobs.Zipcode as Zipcode','weekly_scheduled_jobs.days as days','weekly_scheduled_jobs.time as time','weekly_scheduled_jobs.customer_address as customer_address','weekly_scheduled_jobs.services as services','weekly_scheduled_jobs.id as job_id')
        ->get();

        foreach($Weeklyjobs as $data)
        {
             $dataaaa = array();
             $dayes = array();
             $idsss = explode(',',$data->services);
             $daysids = explode(',',$data->days);
             for($i=0;$i<count($idsss);$i++)
             {
               $getid =  $idsss[$i];
               $service = \App\Servicetype::where('id',$getid)->first();
               array_push($dataaaa, $service->name);
             }
             for($i=0;$i<count($daysids);$i++)
             {
               $getid =  $daysids[$i];
               $Daysname = \App\WorkingDays::where('id',$getid)->first();
               array_push($dayes, $Daysname->day);
             }
             $service_string = implode(' and ', $dataaaa); 
             $data->services = $service_string; 
             $Days_string = implode(' , ', $dayes); 
             $data->days = $Days_string; 
        }
    	return view('supervisor.ScheduledBooking.Weeklyjobs',compact('Weeklyjobs'));
    }   

    public function MonthlyScheduledJobs()
    {    
        $Montlyjobs = \App\MonthlyScheduledJobs::join('users','users.id','=','monthly_scheduled_jobs.customer_id')
        ->select('users.id as customer_id','users.first_name as customer_name','monthly_scheduled_jobs.Zipcode as Zipcode','monthly_scheduled_jobs.date as date','monthly_scheduled_jobs.time as time','monthly_scheduled_jobs.customer_address as customer_address','monthly_scheduled_jobs.services as services','monthly_scheduled_jobs.id as job_id')
        ->get();

        foreach($Montlyjobs as $data)
        {
           $dataaaa = array();
           $idsss = explode(',',$data->services);
           for($i=0;$i<count($idsss);$i++)
           {
             $getid =  $idsss[$i];
             $service = \App\Servicetype::where('id',$getid)->first();
             array_push($dataaaa, $service->name);
           }
           $service_string = implode(' and ', $dataaaa); 
           $data->services = $service_string; 
        }
    	return view('supervisor.ScheduledBooking.Monthlyjobs',compact('Montlyjobs'));
    }

    public function updateWorkingStatus(Request $request)
    {   
       $id = $request->id;
       $workingstatus = \App\WorkingDays::find($request->id);
       if($workingstatus->status == 0)
       {
          $workingstatus->status = 1;
       }
       else
       {
           $workingstatus->status = 0;
       }
       if($workingstatus->update())
       {    
         return "success";
       }
    }
}
