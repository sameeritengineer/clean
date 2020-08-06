<?php

namespace App\Http\Controllers\manager;

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
class JobController extends Controller
{
  public function __construct()
  {
    $this->middleware('manager');
  }

  public function dashboard()
  {
    return view('manager.dashboard');
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

      return view('manager.block',compact('users','blocked_users'));
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
  
  public function unfilled_jobs()
  {
    $countries = \App\Country::get();
    return view('manager.InstantBooking.unfilled_jobs',compact('countries'));
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
                    $NoofJobs = Instant_schedule_job::where('provider_id' , $user->id)->where('status' , '1')->get();
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
                    $review = ProviderReview::where('provider_id', $user->id)->get();     
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
              $NoofJobs = Instant_schedule_job::where('provider_id' , $user->id)->where('status' , '1')->get();
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

              $review = ProviderReview::where('provider_id', $user->id)->get();     
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
      $view = view('manager.InstantBooking.get_provider_list',compact('active_users','unaviable_users','nearby','data'));
      return $view;
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
      return view('manager.InstantBooking.currentJobList',compact('current_jobs'));
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
      return view('manager.InstantBooking.completedJobsList',compact('CompletedJobs'));
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
      return view('manager.InstantBooking.pro_cancelledJobList',compact('P_CanceledJobs'));
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
      return view('manager.InstantBooking.cust_cancelledJobList',compact('C_CanceledJobs'));
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
        $services = Servicetype::whereIn('id',$service_id)->pluck('name');
        $result->Services = $services;
      }

      //return $results;
      return view('manager.InstantBooking.forgot_check_out',compact('results'));
  }

  public function workingdays()
    {   
      $Workingdays = \App\WorkingDays::all();   
      return view('manager.ScheduledBooking.Workingdays',compact('Workingdays'));
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
      return view('manager.ScheduledBooking.Dailyjobs',compact('Dailyjobs'));
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
      return view('manager.ScheduledBooking.Weeklyjobs',compact('Weeklyjobs'));
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
      return view('manager.ScheduledBooking.Monthlyjobs',compact('Montlyjobs'));
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



    public function ViewblockProvider()
    { 
        $blockedproviders = \App\BlockProvider::where('status', 1)->orderBy('id','desc')->get();
        return view('manager.block.CustomerToProvider',compact('blockedproviders'));
    }

  public function blockprovider(Request $request)
  {
      $blockprovider = new \App\BlockProvider;
      $blockprovider->customer_id = $request->customer_id;
      $blockprovider->provider_id = $request->provider_id;
      $blockprovider->status = 1;
      if($blockprovider->save())
      {
        return back()->with('success', "Provider blocked Successfully");
      }
      else
      {
        return "fails";
      }
  }

  public function updateBlockStatus(Request $request)
  {
      $blockprovider = \App\BlockProvider::find($request->id);
      if($blockprovider->delete())
      {
        return 1;
      }
      else
      {
        return 0;
      }
  }

    public function ViewblockCustomer()
    {   
      $blockedcustomers = \App\BlockCustomer::where('status', 1)->orderBy('id','desc')->get();
      return view('manager.block.ProviderToCustomer',compact('blockedcustomers'));
    }

    public function blockcustomer(Request $request)
    {
      $blockcustomer = new \App\BlockCustomer;
      $blockcustomer->provider_id = $request->provider_id;
      $blockcustomer->customer_id = $request->customer_id;
      $blockcustomer->status = 1;
      if($blockcustomer->save())
      {
          return back()->with('success', "Customer blocked Successfully");
      }
      else
      {
          return "fails";
      }
    }

    public function updateCBlockStatus(Request $request)
    {
      $blockcustomer = \App\BlockCustomer::find($request->id);
      if($blockcustomer->delete())
      {
        return 1;
      }
      else
      {
        return 0;
      }
    }

    public function chat()
    {
        $items = \App\Chat::orderBy('id','desc')->get();
        $collection = collect($items);
        $chats = $collection->unique('user_id','provider_id');
        return view('manager.chat.index',compact('chats'));
    }

    public function showChat($user_id,$provider_id)
    {
      $chats = \App\Chat::where('user_id',$user_id)->where('provider_id',$provider_id)->get();
      return view('manager.chat.show',compact('chats'));
    }

    public function worker_notification()
    {
      $blocks = \App\Block::get()->pluck('user_id');
      $users = \App\User::join('user_roles','users.id','=','user_roles.user_id')
      ->join('roles','roles.id','=','user_roles.role_id')
      ->whereHas('roles', function ($query){ $query->where('name','provider'); })
      ->whereNotIn('users.id',$blocks)
      ->select('users.*')->distinct('users.id')->get();
      return view('manager.worker_notification',compact('users'));
    }

  public function sendNotificationToWorker(Request $request)
  { 
    $notificatiotitle = $request->Notification_title;
    $notificatiobody  = strip_tags($request->Notification_body);
    $response = $request->user_id;       
    if(!empty($response))
    { 
      foreach($response as $final)
      {
        $user = \App\User::where('id',$final)->first(); 
        if($user->device_type == "A")
        {
          $url = "https://fcm.googleapis.com/fcm/send";
          $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
          $message = 
          [ 
            "to" => $user->device_id,
            "data" => 
                [
                  "title"     =>  $notificatiotitle,
                  "body"      =>  $notificatiobody,
                  "notificationType" => "send notification to worker"
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
                "title" => $notificatiotitle,
                "body"  => $notificatiobody,
              ],
              "data"=>["notificationType" => "send notification to worker"]
              
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
      return back()->with('success', "Notification sent successfully");
    }
    return back()->with('error', "Something error. Try again");
  }

  public function provider_check_in_out()
  {
    $provider_checks = \App\InstantBooking::join('instant_schedule_jobs','instant_bookings.id','instant_schedule_jobs.job_id')
    ->join('j_ob_checkin_outs','instant_bookings.id','j_ob_checkin_outs.job_id')
    ->join('users','users.id','instant_schedule_jobs.provider_id')
    ->select('instant_bookings.id as instant_booking_id','instant_bookings.zipcode','instant_bookings.customer_address','instant_bookings.date','instant_bookings.time','instant_bookings.Services','instant_schedule_jobs.*','j_ob_checkin_outs.job_id as check_job_id','j_ob_checkin_outs.checkIn','j_ob_checkin_outs.checkOut','users.first_name','users.last_name','users.id as user_id')
    ->get();
    if(count($provider_checks)>0)
    {
      foreach($provider_checks as $provider)
      {
        $services = explode(',',$provider->Services);
        $servives = \App\Servicetype::WhereIn('id',$services)->pluck('name');
        $provider->Services = $servives;
      }
    }
    return view('manager.provider.provider_check',compact('provider_checks'));
  }

  public function users()
  {
    // $users = \App\User::join('user_roles','users.id','=','user_roles.user_id')
    // ->join('roles','roles.id','=','user_roles.role_id')
    // ->whereHas('roles', function ($query){ $query->where('name','!=','admin'); })
    // ->orderBy('users.id','desc')->get();
      //return $users;
    $users = \App\User::whereHas('roles',function($q){ $q->where('name','customer'); })->get();
    return view('manager.users.index',compact('users'));
  }

  public function user_create(Request $request)
  {
    if($request->isMethod('get'))
    {
      $countries = \App\Country::get();
      $roles = \App\Role::where('name','!=','admin')->get();
      return view('manager.users.create',compact('countries','roles'));
    }
    if($request->isMethod('post'))
    {
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

        return redirect()->back()->with(['status'=>'success','message'=>'New Profile has been successfully created!.']);
      }
      else
      {
        return redirect()->back()->with(['status'=>'danger','message'=>'New Profile has not been successfully created!.']);
      }
    }
  }

  public function OpenOpportunity()
  {
    $acceptedjobs = \App\Instant_schedule_job::get(['job_id']);
    $job_ids = array();
    foreach ($acceptedjobs as $key => $value)
    { $job_ids[] = $value->job_id; }

    $OpenOpportunites = \App\InstantBooking::whereNotIn('id' , $job_ids)->where('Parent_id' , '=' ,null)->orderBy('id','desc')->get();
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
    }
    return view('manager.openopportunity',compact('OpenOpportunites'));
  }

  public function show_opportunity(Request $request)
  {
    $dataaaa = array();
    $opportunity = \App\InstantBooking::find($request->id);
    $idsss = explode(',',$opportunity->Services);
    for($i=0;$i<count($idsss);$i++)
    {
      $getid =  $idsss[$i];
      $service = \App\Servicetype::where('id',$getid)->first();
      array_push($dataaaa, $service->name);
    }
    $service_string = implode(' , ', $dataaaa);
    $opportunity->Services_names = $service_string;
    $customername = \App\User::find($opportunity->cutomer_id);
    $opportunity->cust_firstname= $customername->first_name;
    $opportunity->cust_image= $customername->image;

    $role = \App\Role::where('name','provider')->first();
    $providers = array();
    if($role != '')
    {
      $user_role = \App\Userrole::where('role_id',$role->id)->get();
      if(!empty($user_role))
      {
        foreach($user_role as $user)
        {
          $users = \App\User::find($user->user_id);
          if($users != '')
          {
            $user_address = \App\Useraddress::where('userId',$users->id)->get();
            if(!empty($user_address))
            {
              foreach($user_address as $address)
              {
                $zipcode = \App\Zipcode::find($address->zipCode);
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
    return view('manager.showopportunity',compact('opportunity'));
  }

  public function SendNotificationToProvider(Request $request)
  {
      $dataaaa = array();
      $opportunity = \App\InstantBooking::find($request->id);
      $idsss = explode(',',$opportunity->Services);
      for($i=0;$i<count($idsss);$i++)
      {
       $getid =  $idsss[$i];
       $service = \App\Servicetype::where('id',$getid)->first();
       array_push($dataaaa, $service->name);
      }
      $service_string = implode(' , ', $dataaaa);
      $opportunity->Services_names = $service_string;
      
      $notificatiotitle = 'Job Assigned By Admin';
      $notificatiobody  = 'Provider Notification';
      $notificationtype = "assignedByAdmin";
      $user = \App\User::find($request->provider_id);

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
      return response()->json(['status'=>'1','url'=>route('manager::assign_a_job')]);  
  }


  public function providers()
  {
    $role_id = \App\Role::where('name','provider')->first();
    $allserviceproviders = \App\Userrole::where('role_id', $role_id->id)->join('users','users.id','=','user_roles.user_id')->where('status','1')->orderBy('users.id','desc')->get();
    foreach($allserviceproviders as $data)
    {
      $NoofJobs = \App\Instant_schedule_job::where('provider_id' , $data->id)->where('status' , '1')->get();
      if(count($NoofJobs) > 0)
      {
        $data->NoOfJobsCompleted= count($NoofJobs)." Jobs Done ";    //Total No of jobs Completed      
      }
      else
      {
        $data->NoOfJobsCompleted= "No Completed jobs";
      }
      $review = \App\ProviderReview::where('provider_id', $data->id)->get();
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
    //return $allserviceproviders;
    return view('manager.provider.index',compact('allserviceproviders'));
  }


  public function view_jobs($id)
  {
    $jobs = \App\Instant_schedule_job::join('instant_bookings','instant_schedule_jobs.job_id','=','instant_bookings.id')
    ->select('instant_schedule_jobs.*','instant_schedule_jobs.id as instant_schedule_jobs_id','instant_bookings.*')
    ->where('provider_id' , $id)
    ->get();
    return view('manager.provider.view_jobs',compact('jobs'));
  }

  public function show_cmments(Request $request)
    {
      $comments = \App\AdminComment::where('user_id',$request->user_id)->where('job_id',$request->job_id)->pluck('comments');
      if(count($comments)>0)
      {
        foreach($comments as $comment)
        {
          $comment_data[] = "<p>".$comment."</p>"; 
        }
      }
      else
      {
        $comment_data[] = "<p>No Comment</p>"; 
      }
      return $comment_data;
    }

    public function admin_comments(Request $request)
    {
      try
      {
        $comment =  new \App\AdminComment;
        $comment->job_id = $request->job_id ?? Null;
        $comment->user_id = $request->user_id;
        $comment->comments = $request->comments;
        if($comment->save())
        {
          return response()->json(['status'=>true,'message'=>'Admin added comment.']);
        }
        else
        {
          return response()->json(['status'=>false,'message'=>'Try Again.']);
        }
      }
      catch(\Exception $e)
      {
        return response()->json(['status'=>false,'message'=>$e->getMessage()]);
      }
    }

    public function showUser($id)
  {
    $user = \App\User::whereHas('roles',function($q){ $q->where('name','customer'); })->find($id);
    $NoofJobs = \App\Instant_schedule_job::where('provider_id' , $id)->where('status' , '1')->get();
    if(count($NoofJobs) > 0)
    {
      $user->NoOfJobsCompleted= count($NoofJobs)." Jobs Done "; 
    }
    else
    {
      $user->NoOfJobsCompleted= "No Completed jobs";
    }
    $review = \App\ProviderReview::where('provider_id', $id)->get();
    $totalreview  = count($review);      
    if($totalreview > 0)
    {
      $totalrateing = collect($review)->sum('review');
      $user->AverageRating = ($totalrateing / $totalreview);
    }
    else
    {
      $user->AverageRating = 0;
    }
    $comments = \App\AdminComment::where('user_id',$id)->get();
    if(count($comments)>0)
    {
      $user->comments = $comments;
    }
    else
    {
      $user->comments = array();
    }
    $services = \App\Service::where('id',$user->services)->value('name');
    $user->services = $services;

    return view('manager.users.showuser',compact('user'));
  }
}
