<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use URL;
use File;
use Mail;
use App\Approved_Bio;
use App\ProviderProfile;
use App\Providerbio;
use App\Userrole;
use App\Role;
use App\State;
use App\City;
use App\Zipcode;
use App\Useraddress;
use App\Serviceprovider;
use App\Service;
use App\Servicetype;
use App\Instant_schedule_job;
use App\InstantBooking;
use App\ProviderReview;
use App\Sptostype;
use App\JObCheckinOut;
use Imageresize;
use Image;
use App\ProviderLocation;
class ServiceproviderController extends Controller
{

//----------------------add new service provider--------------------------//

    public function genRandomString(){
      $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";$res = "";for ($i = 0; $i < 10; $i++){$res .= $chars[mt_rand(0, strlen($chars)-1)];}return $res;
    }
  
    public function storeServiceProvider(Request $request)
    {
      $this->validate($request,['email'=>'unique:users',],['email.unique' =>'Email is already exist',]);
        $serviceproviders              = new User;
        $serviceproviders->first_name  = $request->fname;
        $serviceproviders->last_name   = $request->lname;
        $serviceproviders->email       = $request->email;
        $serviceproviders->password    = Hash::make(strtolower($request->fname."@123"));
        $serviceproviders->mobile      = $request->mobile;
        $serviceproviders->services    = $request->service_id;
        $serviceproviders->status      = 1;
        $serviceproviders->working_status = 1;
        $serviceproviders->referral_code  =  $this->genRandomString(uniqid());
        if($request->file('profileimage'))
        {                         
/*            $number = hexdec(uniqid());
            $image = $request->file('profileimage');
            $thumbnailImage = Imageresize::make($image);
            $imagePath = public_path().'/profile/';
            $thumbnailImage->resize(150,150);
            $thumbnailImage->save($imagePath.$number.$image->getClientOriginalName());
            $serviceproviders->image = $number.$image->getClientOriginalName();*/
            $image=$request->file('profileimage');
            $extension=$image->getClientOriginalExtension();
            $filename=rand(100,999999).time().'.'.$extension;
            $fullsize_image_path=public_path('profile/'.$filename);
            $thumbnailImage=public_path('thumbnail_images/'.$filename);
            Image::make($image)->save($fullsize_image_path);
            Image::make($image)->resize(800,800)->save($thumbnailImage);
            $serviceproviders->image = $filename;  
        }
        if($serviceproviders->save())
        {
 
          $userAddress = new Useraddress;
          $userAddress->userId  = $serviceproviders->id;
          $userAddress->country = 6;
          $userAddress->address = $request->address;
          $userAddress->state   = $request->stateId;
          $userAddress->city    = $request->cityId;
          $userAddress->zipCode = $request->zipcodeId;

          if($userAddress->save())
          {            
              $role_id = Role::where('name',"provider")->first();
              $add_userroles = new Userrole;
              $add_userroles->user_id = $serviceproviders->id;
              $add_userroles->role_id = $role_id->id;
              if($add_userroles->save())
              {
                if(!empty($request->bio))
                {
                  $add_bio = new Approved_Bio;
                  $add_bio->serviceprovider_id = $serviceproviders->id;
                  $add_bio->Bio = $request->bio;
                  $add_bio->starttime = $request->start_clock;
                  $add_bio->endtime = $request->end_clock;              
                  $add_bio->save();
                }
                if(!empty($request['servicetype']))
                {
                   foreach($request['servicetype'] as $servicetype)
                   {
                     $Sptostype = new Sptostype;
                     $Sptostype->serviceprovider_id = $serviceproviders->id;
                     $Sptostype->service_id = $serviceproviders->services;
                     $Sptostype->servicetype_id = $servicetype;
                     $Sptostype->save();       
                   } 
                }
                $data = array('email' => $request->email);
                Mail::send('approvel.sendUsernamePassword',$data, function($message) use ($data)
                {
                    $message->from(config('mail.username'));
                    $message->to($data['email']);
                    $message->subject('Email Verification');
                });
                return back()->with('success', "Service Provider added successfully");
              }              
          }           
        }
        else
        {
            return "fails";
        }    
    }

    public function viewAllServiceProvider(Request $request){

      $role_id = Role::where('name','provider')->first();
      $allserviceproviders = Userrole::where('role_id', $role_id->id)->join('users','users.id','=','user_roles.user_id')
      ->where('status','1')
      ->orderBy('users.id','desc')
      ->get();
      foreach($allserviceproviders as $data)
      {
        $NoofJobs = Instant_schedule_job::where('provider_id' , $data->id)->where('status' , '1')->get();
        if(count($NoofJobs) > 0)
        {
          $data->NoOfJobsCompleted= count($NoofJobs)." Jobs Done ";    //Total No of jobs Completed      
        }
        else
        {
          $data->NoOfJobsCompleted= "No Completed jobs";
        }
         $review = ProviderReview::where('provider_id', $data->id)->get();
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
      return view('admin.serviceProvider.viewallServiceProvider',compact('allserviceproviders'));
    }

    public function editServiceProvider($id){
    	
      $serviceProvider = User::find($id);
      $services = Service::all();    
      return view('admin.serviceProvider.editserviceprovider',compact('serviceProvider','services'));
    }

    public function destroyServiceProvider(Request $request)
    {
       $id = $request->id;
       $user = User::find($id);

        if($user != null)
        {   
             $del_userservicetype = Sptostype::where('serviceprovider_id', $id)->delete();
             $del_Providerbio = Approved_Bio::where('serviceprovider_id', $id)->delete();
             $del_userrole = Userrole::where('user_id', $id)->delete();
             $del_useraddress = Useraddress::where('userId', $id)->delete();
             $user->delete();
            return "success";
        }
        else
        {
            return "error";
        }   
    }

    public function updateServiceProvider(Request $request)
    {
        $id = $request->id;
        $this->validate($request,
        [
            'email'=>'unique:users,email,' .$id,
        ],
        [
            'email.unique' => 'Email is already exist',
        ]);   
        $serviceproviders              =  User::find($id);
        $serviceproviders->first_name  = $request->fname;
        $serviceproviders->last_name   = $request->lname;
        $serviceproviders->email       = $request->email;
        $serviceproviders->mobile      = $request->mobile;
        $serviceproviders->services    = $request->service_id;
        $serviceproviders->status      = 1;
        if($request->file('profileimage'))
        {                         
   /*       $number = hexdec(uniqid());
            $image = $request->file('profileimage');
            $thumbnailImage = Imageresize::make($image);
            $imagePath = public_path().'/profile/';
            $thumbnailImage->resize(150,150);
            $thumbnailImage->save($imagePath.$number.$image->getClientOriginalName());
            $serviceproviders->image = $number.$image->getClientOriginalName();*/

            $image=$request->file('profileimage');
            $extension=$image->getClientOriginalExtension();
            $filename=rand(100,999999).time().'.'.$extension;
            $fullsize_image_path=public_path('profile/'.$filename);
            $thumbnailImage=public_path('thumbnail_images/'.$filename);
            Image::make($image)->save($fullsize_image_path);
            Image::make($image)->resize(800,800)->save($thumbnailImage);
            $serviceproviders->image = $filename;  
        }
        if($serviceproviders->update())
        { 
            $userAddress = Useraddress::where('userId',$serviceproviders->id)->first();
            $userAddress->country = 6;
            $userAddress->address = $request->address;
            $userAddress->state   = $request->stateId;
            $userAddress->city    = $request->cityId;
            $userAddress->zipCode = $request->zipcodeId;
            if($userAddress->update())
            {
              if(!empty($request->bio))
              {
                  $add_bio = Approved_Bio::where('serviceprovider_id',$serviceproviders->id)->first();
                  if($add_bio != null)
                  {
                      $add_bio->Bio = $request->bio;
                      $add_bio->starttime = $request->start_clock;
                      $add_bio->endtime = $request->end_clock;
                      $add_bio->update();
                  }
                  else
                  {
                      $add_bio = new Approved_Bio;
                      $add_bio->serviceprovider_id = $serviceproviders->id;
                      $add_bio->Bio = $request->bio;
                      $add_bio->starttime = $request->start_clock;
                      $add_bio->endtime = $request->end_clock;
                      $add_bio->save();
                  }
              }
              if(!empty($request->servicetype))
              {
                  Sptostype::where('serviceprovider_id',$serviceproviders->id)->delete();
                  foreach($request->servicetype as $service)
                  {
                      Sptostype::create
                      ([
                          'serviceprovider_id'=>$serviceproviders->id,
                          'service_id' => $serviceproviders->services,
                          'servicetype_id' => $service
                      ]);
                  }
              }
              return back()->with('success', "Service Provider updated successfully");    
          }           
        }
        else
        {
            return "fails";
        }
    }

    public function viewfullprofiledetails(Request $request)
    {
        $providerprofile     = User::find($request->id);
        $provideraddress     = Useraddress::where('userId' , $providerprofile->id)->first();
        $providerstatename   = State::where('id' , $provideraddress->state)->first();
        $providercityname    = City::where('id' , $provideraddress->city)->first();
        $providerzipcodename = Zipcode::where('id' , $provideraddress->zipCode)->first();
        $providerprofile->address = $provideraddress->address;
        $providerprofile->zipCode = $providerzipcodename->zipcode;
        $providerprofile->city = $providercityname->name;
        $providerprofile->state = $providerstatename->name;
        return $providerprofile;
    }

    public function getServicetype(Request $request){

        $service_id = $request->serviceId;    
        $servicetypes = Servicetype::where('service_id',$service_id)->where('status' ,'1')->get();
        $html='';
      
        foreach($servicetypes as $servicetype){
            $html.="<option value=".$servicetype->id.">".$servicetype->name."</option>";         
        }
        return $html;
    }

    public function updateWorkingStatus(Request $request)
    {   
       $id = $request->id;
       $workingstatus = User::find($id);
       if($workingstatus->working_status == 0)
       {
          $workingstatus->working_status = 1;
       }
       else
       {
           $workingstatus->working_status = 0;
       }
       
      if($workingstatus->update())
      {    
         return "success";
      }
    }


//----------------------Unapproved Registration of Service Provider-----------------------------//

  
    public function unApprovedServiceProvider(Request $request){

        $unapprovedproviders = User::where('status', 0)->orderBy('id','desc')->get();
        return view('admin.serviceProvider.allunapprovedserviceprovider',compact('unapprovedproviders'));
    }

    public function updateStatus(Request $request)
    {
        $user = User::find($request->id);
        $user->status = 1;
        $user->working_status = 1;
        if($user->update())
        {   
            $data = array('email' => $user->email);
            Mail::send('approvel.providerRegistrationapprovel',$data, function($message) use ($data)
            {
                $message->from(config('mail.username'));
                $message->to($data['email']);
                $message->subject('Email Verification');
            });
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function DeclineunApprovedProvider(Request $request) 
    {   
       $id = $request->id;
       $declineprovider = User::find($id);
        if($declineprovider != null)
        {    
          $declineprovider->status = '2';
          $declineprovider->update();
           return "success";
        }
        else
        {
            return "error";
        }    
    }

    public function viewProviderProfile(Request $request)
    { 
        $providerprofile     = User::find($request->id);
        $provideraddress     = Useraddress::where('userId' , $providerprofile->id)->first();
        $providerstatename   = State::where('id' , $provideraddress->state)->first();
        $providercityname    = City::where('id' , $provideraddress->city)->first();
        $providerzipcodename = Zipcode::where('id' , $provideraddress->zipCode)->first();
        $providerprofile->address = $provideraddress->address;
        $providerprofile->zipCode = $providerzipcodename->zipcode;
        $providerprofile->city = $providercityname->name;
        $providerprofile->state = $providerstatename->name;
        return $providerprofile;
    }

//-------------------------Unapproved profile Picture of Service Provider---------------------------------//

    public function unApprovedProfileProvider(Request $request)
    {

      $unapprovedProviderProfiles = ProviderProfile::join('users','users.id','=','provider_profiles.serviceprovider_id')->where('provider_profiles.status','0')->select('provider_profiles.*','users.first_name as first_name','users.last_name as last_name','users.email as email')->orderBy('users.id','desc')->get();
      return view('admin.serviceProvider.allunApprovedProfileProvider',compact('unapprovedProviderProfiles'));

    }

    public function updateProfileStatus(Request $request)
    {
      $user = ProviderProfile::find($request->id);            
      $updateimg = User::where('id', $user->serviceprovider_id)->first();
      $updateimg->image = $user->image;
      if($updateimg->update())
        {
          $data = array('email' => $updateimg->email);
          Mail::send('approvel.providerprofileapprovel',$data, function($message) use ($data)
          {
              $message->from(config('mail.username'));
              $message->to($data['email']);
              $message->subject('Email Verification');
          });
          if($user != null)
          {    
              $user->delete();
              return 1;
          }
          else
          {
              return 0;
          }          
        }
        else
        {
            return "error";
        }   
    }

    public function destroyunapproveprofilepic(Request $request)//profile and Bio will added after registration get approved
    {   
       $id = $request->id;
       $user = ProviderProfile::find($id);

        if($user != null)
        {    
            $user->delete();
            return "success";
        }
       else
        {
            return "error";
        }   
    }

//----------------------Unapproved Bio of Service Provider--------------------------//

    public function unApprovedBioProvider(Request $request)
    {
       $unapprovedProviderbios = Providerbio::join('users','users.id','=','providerbios.serviceprovider_id')->where('providerbios.status','0')->select('providerbios.*','users.first_name as first_name','users.last_name as last_name','users.email as email')->orderBy('users.id','desc')->get();
       return view('admin.serviceProvider.allunApprovedBioProvider',compact('unapprovedProviderbios'));
    }

    public function updatebioStatus(Request $request)
    {
      return $request->all();
        $user = Providerbio::find($request->id);
        $useremail = User::find($user->serviceprovider_id);
        $provider_bio = Approved_Bio::where('serviceprovider_id',$user->serviceprovider_id)->first();
        if($provider_bio != null)
        {
          $provider_bio->Bio = $user->Bio;
          $provider_bio->starttime = $user->starttime;
          $provider_bio->endtime = $user->endtime;

          if($provider_bio->update())
          {  
           $data = array('email' => $useremail->email);
            Mail::send('approvel.providerbioapprovel',$data, function($message) use ($data)
            {
                $message->from(config('mail.username'));
                $message->to($data['email']);
                $message->subject('Email Verification');
            });
              if($user != null)
              {    
                  $user->delete();
                  return 1;
              }
              else
              {
                  return 0;
              }
          }
          else
          {
              return "error";
          }  
        }
        else
        {
          $provider_bio = new  Approved_Bio;
          $provider_bio->serviceprovider_id = $user->serviceprovider_id;
          $provider_bio->Bio =$user->Bio;
          $provider_bio->starttime = $user->starttime;
          $provider_bio->endtime = $user->endtime;

          if($provider_bio->save())
          { 
            $data = array('email' => $useremail->email);
            Mail::send('approvel.sendUsernamePassword',$data, function($message) use ($data)
            {
                $message->from(config('mail.username'));
                $message->to($data['email']);
                $message->subject('Email Verification');
            });
            if($user != null)
            {    
                $user->delete();
                return 1;
            }
            else
            {
                return 0;
            }
          }
          else
          {
              return "error";
          } 
        }   
    }

    public function destroyunapprovedBio(Request $request)//profile and Bio will added after registration get approved
    {
       $id = $request->id;
       $user = Providerbio::find($id);

        if($user != null)
        {    
            $user->delete();
            return "success";
        }
       else
        {
            return "error";
        }    
    }

//-----------------List of All Declined Service provider List------------------------//

    public function viewAllDeclineProvider(Request $request)
    {
        $AllDeclineProvider = User::where('status', 2)->get();
        return view('admin.serviceProvider.declineProvider.alldeclineServiceProvider',compact('AllDeclineProvider'));
    }

    public function destroyunApprovedProvider(Request $request) 
    {   
       $id = $request->id;
       $del_user = User::find($id);
       $fullsize_image_path=public_path('profile/'.$del_user->image);
       $thumbnailImage=public_path('thumbnail_images/'.$del_user->image);
       unlink($fullsize_image_path);
       unlink($thumbnailImage);
       if($del_user != null)
       {    
          $del_userprofilepic = ProviderProfile::where('serviceprovider_id', $id)->delete();
          $del_userservicetype = Sptostype::where('serviceprovider_id', $id)->delete();
          $del_Providerbio = Approved_Bio::where('serviceprovider_id', $id)->delete();
          $del_userrole = Userrole::where('user_id', $id)->delete();
          $del_useraddress = Useraddress::where('userId', $id)->delete();
          $del_user->delete();
          return "success";
       }
       else
       {
          return "error";
       }   
    }



    public function showServiceProvider($id)
    {
      $user = User::find($id);
      $NoofJobs = Instant_schedule_job::where('provider_id' , $id)->where('status' , '1')->get();
      if(count($NoofJobs) > 0)
      {
        $user->NoOfJobsCompleted= count($NoofJobs)." Jobs Done ";
        $user->jobs =  $NoofJobs;
      }
      else
      {
        $user->NoOfJobsCompleted= "No Completed jobs";
        $user->jobs = array();
      }
      $review = ProviderReview::where('provider_id', $id)->get();
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
      $comments = \App\AdminComment::where('user_id',$id)->where('job_id','=',Null)->get();
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
      //return $user;
      return view('admin.serviceProvider.show_service_provider',compact('user'));
    }

    public function view_jobs($id)
    {
      $jobs = Instant_schedule_job::join('instant_bookings','instant_schedule_jobs.job_id','=','instant_bookings.id')
      ->select('instant_schedule_jobs.*','instant_schedule_jobs.id as instant_schedule_jobs_id','instant_bookings.*')
      ->where('provider_id' , $id)
      ->get();
      return view('admin.serviceProvider.view_jobs',compact('jobs'));
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

    public function ForgotCheckout()
    {
      $results = JObCheckinOut::join('instant_bookings','j_ob_checkin_outs.job_id','=','instant_bookings.id')
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
      return view('admin.serviceProvider.forgot_check_out',compact('results'));
    }

    public function provider_location()
    {
      $locations = ProviderLocation::with('user')->get();
      return view('admin.serviceProvider.provider_location',compact('locations'));
    }
}