<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Redirect;
use Auth;
use URL;
use File;
use Mail;
use App\Service;
use App\Servicetype;
use App\User;
use App\Userrole;
use App\Role;
use App\ProviderProfile;
use App\Providerbio;
use App\InstantBooking;
use App\Instant_schedule_job;
use App\ProviderReview;
use App\TopRated_Provider;


class PageController extends Controller
{
    public function dashboard()
    {	
    	//total no of services
    	$Service = Service::all();
    	$countservice = count($Service);

    	//total no of service type
    	$Servicetype = Servicetype::all();
    	$countServicetype = count($Servicetype);

    	//total no of service type
    	$role_id = Role::where('name','provider')->first();
    	$allserviceproviders = Userrole::where('role_id', $role_id->id)->join('users','users.id','=','user_roles.user_id')->where('status','1')->get();
    	$countallserviceprovider = count($allserviceproviders);

    	//total no of Un-approved service provider
    	$unapprovedproviders = User::where('status', 0)->get();
    	$countallunapproved = count($unapprovedproviders);

    	//total no of Un-approved profile pic
    	$unapprovedProviderProfiles = ProviderProfile::join('users','users.id','=','provider_profiles.serviceprovider_id')->where('provider_profiles.status','0')->get();
    	$countallunapprovedprofile = count($unapprovedProviderProfiles);

    	//total no of Un-approved Provider bio
    	$unapprovedProviderbios = Providerbio::join('users','users.id','=','providerbios.serviceprovider_id')->where('providerbios.status','0')->get();
    	$countallunapprovedbio = count($unapprovedProviderbios);

        //total no of Declined Provider 
        $declinedProvider = User::where('status', 2)->get();
        $countdeclinedProvider = count($declinedProvider);

        //total no current Opportunity 
        $acceptedjobs = Instant_schedule_job::get(['job_id']);
        $job_ids = array();
        foreach ($acceptedjobs as $key => $value)
        {
           $job_ids[] = $value->job_id;
        }
        $newOpportunity = InstantBooking::whereNotIn('id' , $job_ids)->where('Parent_id' , '=' ,null)->orderBy('id','desc')->get();
        $countnewOpportunity = count($newOpportunity);

        //total no current Jobs 
        $current_jobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
            ->where('instant_schedule_jobs.status', '0')->get();
        $countcurrent_jobs = count($current_jobs);

    	//total no Completed Jobs 
        $CompletedJobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
            ->where('instant_schedule_jobs.status', '1')->get();
        $countCompletedJobs = count($CompletedJobs);

        //total no Provider Cancelled Jobs 
        $P_CanceledJobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
            ->where('instant_schedule_jobs.status', '2')->get();
        $countP_CanceledJobs = count($P_CanceledJobs);

        //total no Customer Cancelled Jobs 
        $C_CanceledJobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
            ->where('instant_schedule_jobs.status', '3')->get();
        $countC_CanceledJobs = count($C_CanceledJobs);

        //All Jobs Status
        $AllJobsLists = Instant_schedule_job::join('users', 'users.id', '=', 'instant_schedule_jobs.cutomer_id')
        ->select('instant_schedule_jobs.id as id','users.first_name as customer_name','instant_schedule_jobs.job_id as job_id','instant_schedule_jobs.status as status')
        ->orderBy('id','desc')
        ->get();

        //Top Service Provider List
        $AllProvider = ProviderReview::all()->groupBy('provider_id');
        $count = count($AllProvider);
        for ($i=0; $i < $count ; $i++)
        {
          $provider_id = array();
          $Avr_Rating = array();
          foreach ($AllProvider as $key => $value) 
          {   
              $review = ProviderReview::where('provider_id', $key)->get();
              $totalreview  = count($review);
              $totalrateing = collect($review)->sum('review');
              $rating = ($totalrateing / $totalreview);
              $x = number_format($rating, 1);
              array_push($provider_id, $key);
              array_push($Avr_Rating, $x);   
          } 
        }
        $allcombine = array_combine($provider_id, $Avr_Rating);
        foreach ($allcombine as $key => $value) 
        {
          $providerdetails = TopRated_Provider::where('Provider_id',$key)->first();
          if($providerdetails != null)
          {
             $providerdetails->Avg_Rating = $value;
             $providerdetails->update();
          }
          else
          { 
              $providerdetails = new  TopRated_Provider;
              $providerdetails->Provider_id = $key;
              $providerdetails->Avg_Rating = $value;
              $providerdetails->save();
          }
        }

        $RecentCleaners = TopRated_Provider::join('users', 'users.id', '=', 'top_rated__providers.Provider_id')
        ->select('users.first_name as first_name','users.image as image','top_rated__providers.Avg_Rating as Avg_Rating')
        ->orderBy('Avg_Rating','desc')
        ->get();

        return view('admin.dashboard', compact('countservice','countServicetype','countallserviceprovider','countallunapproved','countallunapprovedprofile','countallunapprovedbio','countdeclinedProvider','countnewOpportunity','countcurrent_jobs','countCompletedJobs','countP_CanceledJobs','countC_CanceledJobs','AllJobsLists','RecentCleaners'));
    }
}
