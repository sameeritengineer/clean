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
use App\WorkingDays;
use App\DailyScheduledJobs;
use App\WeeklyScheduledJobs;
use App\MonthlyScheduledJobs;
use App\Servicetype;
use App\Serviceprovider;
use App\Sptostype;
use App\ServicePrice;
use App\InstantBooking;
use App\Instant_schedule_job;


class ScheduledBookingController extends Controller
{
    public function workingdays()
    {   
    	$Workingdays = WorkingDays::all();   
    	return view('admin.ScheduledBooking.Workingdays',compact('Workingdays'));
    }  
    public function addworkingday(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'day'=>'required|unique:working_days',
        ]);
        if ($validator->fails())
        {
           return Response::json(['errors' => $validator->errors()]);
        }
        else
        {
            $workingday = new WorkingDays;
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
        $workingDay = WorkingDays::find($request->id);
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
    	$Dailyjobs = DailyScheduledJobs::join('users','users.id','=','daily_scheduled_jobs.customer_id')
        ->select('users.id as customer_id','users.first_name as customer_name','daily_scheduled_jobs.Zipcode as Zipcode','daily_scheduled_jobs.daily_time as daily_time','daily_scheduled_jobs.customer_address as customer_address','daily_scheduled_jobs.services as services','daily_scheduled_jobs.id as Job_id')
        ->get();

          foreach($Dailyjobs as $data)
          {
             $dataaaa = array();
             $idsss = explode(',',$data->services);
             for($i=0;$i<count($idsss);$i++)
             {
               $getid =  $idsss[$i];
               $service = Servicetype::where('id',$getid)->first();
               array_push($dataaaa, $service->name);
             }
             $service_string = implode(' and ', $dataaaa); 
             $data->services = $service_string; 
          }
    	return view('admin.ScheduledBooking.Dailyjobs',compact('Dailyjobs'));
    } 

    public function WeeklyScheduledJobs()
    {   
        $Weeklyjobs = WeeklyScheduledJobs::join('users','users.id','=','weekly_scheduled_jobs.customer_id')
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
               $service = Servicetype::where('id',$getid)->first();
               array_push($dataaaa, $service->name);
             }
             for($i=0;$i<count($daysids);$i++)
             {
               $getid =  $daysids[$i];
               $Daysname = WorkingDays::where('id',$getid)->first();
               array_push($dayes, $Daysname->day);
             }
             $service_string = implode(' and ', $dataaaa); 
             $data->services = $service_string; 
             $Days_string = implode(' , ', $dayes); 
             $data->days = $Days_string; 
        }
    	return view('admin.ScheduledBooking.Weeklyjobs',compact('Weeklyjobs'));
    }   

    public function MonthlyScheduledJobs()
    {    
        $Montlyjobs = MonthlyScheduledJobs::join('users','users.id','=','monthly_scheduled_jobs.customer_id')
        ->select('users.id as customer_id','users.first_name as customer_name','monthly_scheduled_jobs.Zipcode as Zipcode','monthly_scheduled_jobs.date as date','monthly_scheduled_jobs.time as time','monthly_scheduled_jobs.customer_address as customer_address','monthly_scheduled_jobs.services as services','monthly_scheduled_jobs.id as job_id')
        ->get();

        foreach($Montlyjobs as $data)
        {
           $dataaaa = array();
           $idsss = explode(',',$data->services);
           for($i=0;$i<count($idsss);$i++)
           {
             $getid =  $idsss[$i];
             $service = Servicetype::where('id',$getid)->first();
             array_push($dataaaa, $service->name);
           }
           $service_string = implode(' and ', $dataaaa); 
           $data->services = $service_string; 
        }
    	return view('admin.ScheduledBooking.Monthlyjobs',compact('Montlyjobs'));
    }

    public function updateWorkingStatus(Request $request)
    {   
       $id = $request->id;
       $workingstatus = WorkingDays::find($request->id);
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
