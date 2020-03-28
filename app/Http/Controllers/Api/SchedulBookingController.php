<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Service;
use App\Servicetype;
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
use App\WorkingDays;
use App\DailyScheduledJobs;
use App\WeeklyScheduledJobs;
use App\MonthlyScheduledJobs;
use Imageresize;
use DB;
use Auth;
use URL;
use File;
use Mail;
use Redirect;
use App\Common\Notification;

class SchedulBookingController extends Controller
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

    public function allworkingdays()
    {
      $allworkingdays = WorkingDays::where('status' , 1)->orderBy('id','ASC')->get(['id','day']);
      if(count($allworkingdays)>0)
      {
       return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all Working Days","payload"=> $allworkingdays]);
      }
      else
      {
       return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no Working Days"]);   
      }    
    }

    public function DailyScheduleingJob(Request $request)
    {
      $input = $request->all();
      $rules = array('customer_id' => 'required','time'=> 'required','zipcode' => 'required','address'=> 'required','service_ids'=> 'required');
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {
        $user = User::find($request->customer_id);
        if($user != null)
        {
          $zipcode = Zipcode::where('zipcode' ,$input['zipcode'])->first(); 
          if(!empty($zipcode))
          { 
              $dailyjob = new DailyScheduledJobs;
              $dailyjob->customer_id        = $input['customer_id'];
              $dailyjob->Zipcode            = $input['zipcode'];
              $dailyjob->daily_time         = $input['time'];
              $dailyjob->customer_address   = $input['address'];
              $dailyjob->services           = $input['service_ids'];
              if($dailyjob->save())
              {
                  return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "Daily Job Scheduled" , 'Job_id' => $dailyjob->id ]);
              }
              else
              {
                  return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Went Wrong']);
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

    public function WeeklyScheduleingJob(Request $request)
    {
      $input = $request->all();
      $rules = array('customer_id' => 'required','zipcode' => 'required','address'=> 'required','service_ids'=> 'required','DateTime'=> 'required',);
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {
        $user = User::find($request->customer_id);
        if($user != null)
        {
          $zipcode = Zipcode::where('zipcode' ,$input['zipcode'])->first(); 
          if(!empty($zipcode))
          {
              $daystime = $input['DateTime'];
              $days = array();
              $times = array();
               foreach($daystime as $dt)
               {   
                  $day = $dt['day'];
                  $time = $dt['time'];
                  
                  array_push($days, $day);
                  array_push($times, $time);   
               }

                $daysList = implode(',', $days);
                $TimeList = implode(',', $times);
                $weeklyjob = new WeeklyScheduledJobs;
                $weeklyjob->customer_id        = $input['customer_id'];
                $weeklyjob->Zipcode            = $input['zipcode'];
                $weeklyjob->days               = $daysList;
                $weeklyjob->time               = $TimeList;
                $weeklyjob->services           = $input['service_ids'];
                $weeklyjob->customer_address   = $input['address'];
                
                if($weeklyjob->save())
                {
                    return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "Weekly Job Scheduled" ,'Job_id' => $weeklyjob->id]);
                }
                else
                {
                    return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Went Wrong']);
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

    public function MonthlyScheduleingJob(Request $request)
    {
      $input = $request->all();
      $rules = array('customer_id' => 'required','datetime' => 'required','zipcode' => 'required','address'=> 'required','service_ids'=> 'required');
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {
         $user = User::find($request->customer_id);
        if($user != null)
        {
          $zipcode = Zipcode::where('zipcode' ,$input['zipcode'])->first(); 
          if(!empty($zipcode))
          {
              $datestime = $input['datetime'];
              $dates = array();
              $times = array();
              foreach($datestime as $dt)
              {   
                  $data = $dt['date'];
                  $time = $dt['time'];
                  
                  array_push($dates, $data);
                  array_push($times, $time);   
              }
              $daysList = implode(',', $dates);
              $TimeList = implode(',', $times);
              $monthlyjob = new MonthlyScheduledJobs;
              $monthlyjob->customer_id        = $input['customer_id'];
              $monthlyjob->Zipcode            = $input['zipcode'];
              $monthlyjob->date               = $daysList;
              $monthlyjob->time               = $TimeList;
              $monthlyjob->services           = $input['service_ids'];
              $monthlyjob->customer_address   = $input['address'];
              
              if($monthlyjob->save())
              {
                  return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "Monthly Job Scheduled" ,'Job_id' => $monthlyjob->id]);
              }
              else
              {
                  return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Went Wrong']);
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
}
