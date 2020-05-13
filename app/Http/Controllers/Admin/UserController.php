<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Useraddress;
use App\Role;
use App\Userrole;
use App\Service;
use App\Servicetype;
use App\Serviceprovider;
use App\Sptostype;
use App\ServicePrice;
use App\InstantBooking;
use App\Instant_schedule_job;
use App\provider_faq;
use App\ProviderFaqSpanish;
use App\customer_faq;
use App\CustomerFaqSpanish;
use App\DiscountCodes;
use App\BlockProvider;
use App\BlockCustomer;
use App\Block;
use App\Otp;
use Response;
use Auth;
use Mail;
use URL;
use File;

class UserController extends Controller
{
  public function saveUser(Request $request)
	{
		$this->validate($request,
    [
      'first_name'=> 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email'     => 'required|string|email|max:255|unique:users',
      'password'  => 'required|string|min:6',
      'mobile'    => 'required',      
    ]);

		$users = new User;
    $users->first_name = $request->first_name;
    $users->last_name = $request->last_name;
    $users->email = $request->email;
    $users->password = Hash::make($request->password);
    $users->mobile = $request->mobile;
    $users->device_type = 0;
    $users->status = 1;
    if($users->save())
    { 
      $getuserRole = $request->role;
      $adduserRole = new Userrole;
      $adduserRole->user_id = $users->id;
      $adduserRole->role_id = $getuserRole;
      if($adduserRole->save())
      {
        return back()->with('success', "User added successfully");
      }                                                               
    }
    else
    {
      return "fails";
    }
	}

  public function showAllUser(Request $request)
  {
    $role_id = Role::where('name', 'customer')->first();
    $users = Userrole::where('role_id', $role_id->id)->join('users','users.id','=','user_roles.user_id')->get();
    return view('admin.user.alluser',compact('users','role_id'));
  }

        
  public function showEditUser($id)
  {
    $user   = User::find($id);
    $roleid = Userrole::where('user_id', $user->id)->first();
    $roles  = Role::all();
    return view('admin.user.edituser',compact('user','roles','roleid'));
  }

  public function updateUser(Request $request)
  {
    $id = $request->id;
    $user =  User::find($id);
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    $user->mobile = $request->mobile;
    $password = $request->password;
    if(!empty($password))
    {
      $user->password = Hash::make($password);
    }
    if($user->update())
    {
      $user_id =$id;
      $deleteuser_Role = Userrole::where('user_id', $user_id)->delete();
      if($deleteuser_Role)
      {
        $getuserRole = $request->role;
        $adduserRole = new Userrole;
        $adduserRole->user_id = $user_id;
        $adduserRole->role_id = $getuserRole;
        if($adduserRole->save())
        {
          return back()->with('success', "User Updates successfully");
        }  
      }                                                                             
    }
    else
    {
      return "fails";
    }
  }

  public function destroyUser(Request $request)
  {
    $id = $request->id;
    $user = User::find($id);
    if($user != null)
    {
      $useraddress = Useraddress::where('userId', $id)->delete();
      $user->delete();
      return "success";
    }
    else
    {
      return "error";
    }   
  }

 //-----------------------Role--------------------------//

  public function role()
  {
    $roles = Role::orderBy('id','desc')->get();
    return view('admin.role.role',compact('roles'));
  }

  public function storeRole(Request $request)
  {
    $validator = Validator::make($request->all(),
    [
      'name'=>'unique:roles',
    ]);
    if ($validator->fails())
    {
      return Response::json(['errors' => $validator->errors()]);
    }
    else
    {
      $roles = new Role;
      $roles->name = $request->name;
      if($roles->save())
      {
        return 1;
      }
      else
      {
        return 0;
      }
    }       
  }

  public function editRole(Request $request)
  {
    $roles = Role::find($request->id);
    return  $roles;
  }

  public function updateRole(Request $request)
  {
    $validator = Validator::make($request->all(),
    [
      'name'=>'unique:roles,name,'.$request->id,
    ]);
    if ($validator->fails())
    {
      return Response::json(['errors' => $validator->errors()]);
    }
    else
    {
      $roles = Role::find($request->id);
      $roles->name = $request->name; 
      if($roles->update())
      {
        return 1;
      }
      else
      {
        return 0;
      } 
    }             
  }
    
  public function destroyRole(Request $request)
  {
    $roles = Role::find($request->id);
    if($roles->delete())
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }

  //-----------------------Block provider From Customer--------------------------//

  public function ViewblockProvider()
  { 
    $blockedproviders = BlockProvider::where('status', 1)->orderBy('id','desc')->get();
    return view('admin.block.CustomerToProvider',compact('blockedproviders'));
  }

  public function blockprovider(Request $request)
  {
    $blockprovider = new BlockProvider;
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
    $blockprovider = BlockProvider::find($request->id);
    if($blockprovider->delete())
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }

  //-----------------------Block Customer From Provider --------------------------//

  public function ViewblockCustomer()
  {   
    $blockedcustomers = BlockCustomer::where('status', 1)->orderBy('id','desc')->get();
    return view('admin.block.ProviderToCustomer',compact('blockedcustomers'));
  }

  public function blockcustomer(Request $request)
  {
    $blockcustomer = new BlockCustomer;
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
    $blockcustomer = BlockCustomer::find($request->id);
    if($blockcustomer->delete())
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }


  //-----------------------Discount--------------------------//

  public function discount()
  {
    $discountCodes = DiscountCodes::orderBy('id','desc')->get();
    return view('admin.discount.discount',compact('discountCodes'));
  }

  public function storeDiscount(Request $request)
  {
    $validator = Validator::make($request->all(),
    [
      'codename'=>'unique:discount_codes,code_name','discount'=> 'required',
    ]);
    if ($validator->fails())
    {
      return Response::json(['errors' => $validator->errors()]);
    }
    else
    {
      $discountcode = new DiscountCodes;
      $discountcode->code_name = $request->codename;
      $discountcode->discount = $request->discount;
      $discountcode->status = 1;
      if($discountcode->save())
      {
        return 1;
      }
      else
      {
        return 0;
      }
    }       
  }

  public function destroyDiscount(Request $request)
  {
    $discount = DiscountCodes::find($request->id);
    if($discount->delete())
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }

  public function updateDiscountStatus(Request $request)
  {
    $discount = DiscountCodes::find($request->id);
    if($discount->status == 0)
    {
      $discount->status = 1;
    }
    else
    {
      $discount->status = 0;
    }
    if($discount->update())
    {    
      return "success";
    }
  }


//-----------------------Discount--------------------------//

  public function notification()
  {
    return view('admin.notifications.notification');
  }

  public function worker_notification()
  {
    // $users = User::whereHas('roles', function ($query)
    // {
    //   $query->where('name', '=', 'provider');
    // })->get();
    $blocks = \App\Block::get()->pluck('user_id');
    $users = User::join('user_roles','users.id','=','user_roles.user_id')
    ->join('roles','roles.id','=','user_roles.role_id')
    ->whereHas('roles', function ($query){ $query->where('name','provider'); })
    ->whereNotIn('users.id',$blocks)
    ->select('users.*')->distinct('users.id')->get();
    return view('admin.notifications.worker_notification',compact('users'));
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

  public function SendNotification(Request $request)
  {   
    $notificatiotitle = $request->Notification_title;
    $notificatiobody  = strip_tags($request->Notification_body);
    $Sendto = $request->select;
    if($Sendto == 1)
    {
      // $role_id = Role::where('name', 'provider')->first();
      // $response = Userrole::where('role_id', $role_id->id)->join('users','users.id','=','user_roles.user_id')->get();
      $blocks = \App\Block::get()->pluck('user_id');
      $response = User::join('user_roles','users.id','=','user_roles.user_id')
      ->join('roles','roles.id','=','user_roles.role_id')
      ->whereHas('roles', function ($query){ $query->where('name','provider'); })
      ->whereNotIn('users.id',$blocks)
      ->select('users.id as user_id')->distinct('users.id')->get();
    }
    elseif($Sendto == 2)
    {
      // $role_id = Role::where('name', 'customer')->first();
      // $response = Userrole::where('role_id', $role_id->id)->join('users','users.id','=','user_roles.user_id')->get();
      $blocks = \App\Block::get()->pluck('user_id');
      $response = User::join('user_roles','users.id','=','user_roles.user_id')
      ->join('roles','roles.id','=','user_roles.role_id')
      ->whereHas('roles', function ($query){ $query->where('name','customer'); })
      ->whereNotIn('users.id',$blocks)
      ->select('users.id as user_id')->distinct('users.id')->get();
    }
    elseif($Sendto == 3)
    {
      //$response = Userrole::all();
      $blocks = \App\Block::get()->pluck('user_id');
      $response = User::join('user_roles','users.id','=','user_roles.user_id')
      ->join('roles','roles.id','=','user_roles.role_id')
      ->whereHas('roles', function ($query){ $query->whereIn('name',['provider','customer']); })
      ->whereNotIn('users.id',$blocks)
      ->select('users.id as user_id')->distinct('users.id')->get();
    }
    else
    {
      return back()->with('error', "Something error. Try again");
    }       
    if(!empty($response))
    { 
      foreach($response as $final)
      {
        $user = User::where('id',$final->user_id)->first(); 
        if($user->device_type == "A")
        {
          $url = "https://fcm.googleapis.com/fcm/send";
          $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
          $message = 
          [ 
            "to" => $user->device_id,
            "data" => 
            [
              "title" =>  $notificatiotitle,
              "body"  =>  $notificatiobody,
              "notificationType" => "send notification to all"
            ]
          ];
          $json = json_encode($message);
          $headers = array('Content-Type: application/json','Authorization: key='. $serverKey );
          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, $url);
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, $json);
          curl_setopt( $ch, CURLOPT_TIMEOUT_MS, 10000);
          $result = curl_exec($ch);
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
            "data"=>["notificationType" => "send notification to all"]
          ];
          $json = json_encode($message);
          $headers = array('Content-Type: application/json','Authorization: key='. $serverKey);
          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, $url);
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, $json);
          curl_setopt( $ch, CURLOPT_TIMEOUT_MS, 10000);
          $result = curl_exec($ch);
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
  }

//-----------------Faq's for Provider ------------------//

  public function provider_faq()
  {
    $provider_faqs = provider_faq::orderBy('id','desc')->get();
    return view('admin.faq.provider_faq',compact('provider_faqs'));
  }

  public function addproviderfaq(Request $request)
  {
    $validator = Validator::make($request->all(),
    [       
      'question' => 'required',
      'description'   => 'required',
    ]);
    if ($validator->fails())
    {
      return Response::json(['errors' => $validator->errors()]);
    }
    else
    {
      $provider_faq = new provider_faq;
      $provider_faq->question = $request->question;
      $provider_faq->answer = $request->description;
      if($provider_faq->save())
      {   
        $provider_faq_spanish = new ProviderFaqSpanish;
        $provider_faq_spanish->pro_faqId = $provider_faq->id;
        $provider_faq_spanish->question = $request->spanish_question;
        $provider_faq_spanish->answer = $request->spanish_description;
        if($provider_faq_spanish->save())
        {
          return 1;
        }
        else
        {
          return 0;
        }
      }
    }       
  }

  public function editProviderFaq(Request $request)
  {
    $providerFaqs = provider_faq::find($request->id);
    $spanishlng = ProviderFaqSpanish::where('pro_faqId', $providerFaqs->id)->first();
    if(!empty($spanishlng))
    {
      $providerFaqs->spanishquestion = $spanishlng->question;
      $providerFaqs->spanishanswer = $spanishlng->answer;
    }
    else
    {
      $providerFaqs->spanishquestion = null;
      $providerFaqs->spanishanswer = null;
    }
    return  $providerFaqs;
  }

  public function updateProviderFaq(Request $request)
  {
    $validator = Validator::make($request->all(),
    [
      'id'       => 'required',
      'name' => 'required',
      'description'=> 'required',
    ]);
    if ($validator->fails())
    {
      return Response::json(['errors' => $validator->errors()]);
    }
    else
    {
      $providerfaq = provider_faq::find($request->id);
      $providerfaq->question = $request->name; 
      $providerfaq->answer = $request->description; 
      if($providerfaq->update())
      {
        $provider_faq_spanish = ProviderFaqSpanish::where('pro_faqId',$request->id)->first();
        $provider_faq_spanish->question = $request->spanish_question;
        $provider_faq_spanish->answer = $request->editspanish_description;
        if($provider_faq_spanish->save())
        {
          return 1;
        }
        else
        {
          return 0;
        }
      }
    }             
  }

  public function destroyProviderfaq(Request $request)
  {
    $providerFaq = provider_faq::find($request->id);
    if($providerFaq->delete())
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }


 //-----------------Faq's for Customer-------------------//

  public function customer_faq()
  {
    $customer_faqs = customer_faq::orderBy('id','desc')->get();
    return view('admin.faq.customer_faq',compact('customer_faqs'));
  }

  public function addcustomerfaq(Request $request)
  {
    $validator = Validator::make($request->all(),
    [       
      'question' => 'required',
      'description'   => 'required',
    ]);
    if ($validator->fails())
    {
      return Response::json(['errors' => $validator->errors()]);
    }
    else
    {
      $customer_faq = new customer_faq;
      $customer_faq->question = $request->question;
      $customer_faq->answer = $request->description;
      if($customer_faq->save())
      {   
        $customer_faq_spanish = new CustomerFaqSpanish;
        $customer_faq_spanish->cust_faqId = $customer_faq->id;
        $customer_faq_spanish->question = $request->spanish_question;
        $customer_faq_spanish->answer = $request->spanish_description;
        if($customer_faq_spanish->save())
        {
          return 1;
        }
        else
        {
          return 0;
        }
      }
    }       
  }

  public function editcustomerFaq(Request $request)
  {
    $customerFaqs = customer_faq::find($request->id);
    $spanishlng = CustomerFaqSpanish::where('cust_faqId', $customerFaqs->id)->first();
    if(!empty($spanishlng))
    {
      $customerFaqs->spanishquestion = $spanishlng->question;
      $customerFaqs->spanishanswer = $spanishlng->answer;
    }
    else
    {
      $customerFaqs->spanishquestion = null;
      $customerFaqs->spanishanswer = null;
    }
    return  $customerFaqs;
  }

  public function updatecustomerFaq(Request $request)
  {
    $validator = Validator::make($request->all(),
    [
      'id'       => 'required',
      'name' => 'required',
      'description'=> 'required',
    ]);
    if ($validator->fails())
    {
      return Response::json(['errors' => $validator->errors()]);
    }
    else
    {
      $customerfaq = customer_faq::find($request->id);
      $customerfaq->question = $request->name; 
      $customerfaq->answer = $request->description; 
      if($customerfaq->update())
      {
        $customer_faq_spanish = CustomerFaqSpanish::where('cust_faqId', $request->id)->first();
        $customer_faq_spanish->question = $request->spanish_question;
        $customer_faq_spanish->answer = $request->editspanish_description;
        if($customer_faq_spanish->save())
        {
          return 1;
        }
        else
        {
          return 0;
        }
      }
    }             
  }

  public function destroycustomerfaq(Request $request)
  {
    $customerFaq = customer_faq::find($request->id);
    if($customerFaq->delete())
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }

  public function showUser($id)
  {
    $user = User::find($id);
    $NoofJobs = Instant_schedule_job::where('provider_id' , $id)->where('status' , '1')->get();
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
    //return $user;
    return view('admin.user.showuser',compact('user'));
  }

  public function updateStatus(Request $request)
  {
    $id = $request->id;
    $status = User::find($id);
    if($status->status == 0)
    {
      $status->status = 1;
    }
    else
    {
      $status->status = 0;
    }
    if($status->update())
    {    
      return "success";
    }
  } 

  public function block()
  {
    $blocked_users = Block::join('users','blocks.user_id','=','users.id')
    ->join('user_roles','users.id','=','user_roles.user_id')
    ->join('roles','roles.id','=','user_roles.role_id')
    ->select('users.*','blocks.status as block_status','blocks.time','roles.name as role_name','blocks.id as block_id')
    ->where('blocks.time','=',Null)
    ->get();

    $blocks = Block::where('time','=',Null)->get()->pluck('user_id');
    $users = User::join('user_roles','users.id','=','user_roles.user_id')
    ->join('roles','roles.id','=','user_roles.role_id')
    ->whereHas('roles', function ($query){ $query->whereIn('name',['customer','provider']); })
    ->whereNotIn('users.id',$blocks)
    ->select('users.*','roles.name as role_name')->distinct('users.id')
    ->get();

    return view('admin.block.block',compact('users','blocked_users'));
  } 

  public function add_block(Request $request)
  {
    $validator = Validator::make($request->all(),
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
      Block::create($request->all());
      return back();
    }
  }

  public function unblock_list(Request $request)
  {
    $block = Block::find($request->id);
    if($block->delete())
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }

  public function ban_ip(Request $request)
  {
    $id = $request->id;
    $status = User::find($id);
    if($status->ban_ip == "0")
    {
      $status->ban_ip = "1";
    }
    else
    {
      $status->ban_ip = "0";
    }
    if($status->update())
    {    
      return "success";
    }
  }

  public function chat()
  {
    $items = \App\Chat::orderBy('id','desc')->get();
	  $collection = collect($items);
    $chats = $collection->unique('user_id','provider_id');
    //return $uniqueItems;
	//return $chats;
    return view('admin.chat.index',compact('chats'));
  }

  public function showChat($user_id,$provider_id)
  {
    $chats = \App\Chat::where('user_id',$user_id)->where('provider_id',$provider_id)->get();
    return view('admin.chat.show',compact('chats'));
  }
}
