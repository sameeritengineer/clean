<?php

namespace App\Http\Controllers\Support;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkerController extends Controller
{
  public function __construct()
  {
    $this->middleware('support');
  }

  public function dashboard()
  {
    $users = \App\User::join('user_roles','users.id','=','user_roles.user_id')
    ->join('roles','roles.id','=','user_roles.role_id')
    ->whereHas('roles', function ($query){ $query->where('name','customer'); })
    ->orderBy('users.id','desc')->count();
    return view('support.dashboard',compact('users'));
  }

    public function ViewblockProvider()
    { 
        $blockedproviders = \App\BlockProvider::where('status', 1)->orderBy('id','desc')->get();
        return view('support.block.CustomerToProvider',compact('blockedproviders'));
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
      return view('support.block.ProviderToCustomer',compact('blockedcustomers'));
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
        return view('support.chat.index',compact('chats'));
    }

    public function showChat($user_id,$provider_id)
    {
      $chats = \App\Chat::where('user_id',$user_id)->where('provider_id',$provider_id)->get();
      return view('support.chat.show',compact('chats'));
    }

    public function worker_notification()
  	{
	    $blocks = \App\Block::get()->pluck('user_id');
	    $users = \App\User::join('user_roles','users.id','=','user_roles.user_id')
	    ->join('roles','roles.id','=','user_roles.role_id')
	    ->whereHas('roles', function ($query){ $query->where('name','provider'); })
	    ->whereNotIn('users.id',$blocks)
	    ->select('users.*')->distinct('users.id')->get();
	    return view('support.worker_notification',compact('users'));
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
    return view('support.provider.provider_check',compact('provider_checks'));
  }

  public function users()
  {
    // $users = \App\User::join('user_roles','users.id','=','user_roles.user_id')
    // ->join('roles','roles.id','=','user_roles.role_id')
    // ->whereHas('roles', function ($query){ $query->where('name','=','customer'); })
    // ->orderBy('users.id','desc')->get();
    $users = \App\User::whereHas('roles',function($q){ $q->where('name','customer'); })->get();
    //return $users;
    return view('support.users.index',compact('users'));
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
    return view('support.provider.index',compact('allserviceproviders'));
  }

  public function view_jobs($id)
  {
    $jobs = \App\Instant_schedule_job::join('instant_bookings','instant_schedule_jobs.job_id','=','instant_bookings.id')
    ->select('instant_schedule_jobs.*','instant_schedule_jobs.id as instant_schedule_jobs_id','instant_bookings.*')
    ->where('provider_id' , $id)
    ->get();
    return view('support.provider.view_jobs',compact('jobs'));
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

  public function user_create(Request $request)
  {
    if($request->isMethod('get'))
    {
      $countries = \App\Country::get();
      $roles = \App\Role::where('name','!=','admin')->get();
      return view('support.users.create',compact('countries','roles'));
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
    return view('support.openopportunity',compact('OpenOpportunites'));
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
    return view('support.showopportunity',compact('opportunity'));
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
      return response()->json(['status'=>'1','url'=>route('support::assign_a_job')]);  
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

    return view('support.users.showuser',compact('user'));
  }
}
