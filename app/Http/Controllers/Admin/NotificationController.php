<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Block;
use App\AdminSession;
class NotificationController extends Controller
{
    public function prevent_notification()
    {
    	$blocks = Block::get()->pluck('user_id');
    	$users = User::join('user_roles','users.id','=','user_roles.user_id')
    	->join('roles','roles.id','=','user_roles.role_id')
    	->whereHas('roles', function ($query){ $query->where('name','provider'); })
    	->whereNotIn('users.id',$blocks)
    	->select('users.*','roles.name as role_name')->distinct('users.id')->get();

    	$blocked = User::join('blocks','users.id','=','blocks.user_id')->where('blocks.time','!=',Null)->get();

    	return view('admin.notifications.prevent_notification',compact('users','blocked'));
    }


    public function admin_session(Request $request)
    {
        try
        {
            $session = AdminSession::where('admin_id',auth()->user()->id)->first();
            if($session != null)
            {
                $session->admin_id = auth()->user()->id;
                $session->url = $request->current_url;
                $session->status = $request->status;
                if($session->save())
                {
                    return "true";
                }
                else
                {
                    return "false";
                }
            }
            else
            {
                $new = new AdminSession;
                $new->admin_id = auth()->user()->id;
                $new->url = $request->current_url;
                $new->status = $request->status;
                if($new->save())
                {
                    return "true";
                }
                else
                {
                    return "false";
                }
            }    
        }
        catch (Exception $e)
        {
            return response()->json($e->getMessage());    
        } 
    }

    public function job_assigned_by_admin_provider_noti(Request $request)
    {
      //return $request->all();
      $dataaaa = array();
      $opportunity = \App\InstantBooking::find($request->job_id);
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
      $user = \App\User::whereIn('id',$request->provider_id)->first();
      if(isset($request->nearby_message) && !empty($request->nearby_message))
      {
        $message = $request->nearby_message;
      }
      elseif(isset($request->unavailable_message) && !empty($request->unavailable_message))
      {
        $message = $request->unavailable_message;
      }
      elseif(isset($request->active_message) && !empty($request->active_message))
      {
        $message = $request->active_message;
      }
      else
      {
        $message = '';
      }
      return $message;
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
                "message"=> $message,
              ]
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
      return "success";



        // $users = User::whereIn('id',$request->provider_id)->get();
        // if(count($users)>0)
        // {
        // foreach($users as $user)
        //   {
        //     if($user->device_type == "A")
        //     {
        //       $url = "https://fcm.googleapis.com/fcm/send";
        //       $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
        //       $message = 
        //       [ 
        //         "to" => $user->device_id,
        //         "data" => 
        //             [
        //               "title"     =>  "Job assigned by admin",
        //               "body"      =>  "Job assigned by admin",
        //               "notificationType" => "send notification to worker"
        //             ],
                
        //       ];
        //       $json = json_encode($message);
        //       $headers = array(
        //         'Content-Type: application/json',
        //         'Authorization: key='. $serverKey
        //       );
        //       $ch = curl_init();
        //       curl_setopt( $ch,CURLOPT_URL, $url);
        //       curl_setopt( $ch,CURLOPT_POST, true );
        //       curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        //       curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        //       curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        //       curl_setopt( $ch,CURLOPT_POSTFIELDS, $json);
        //       //Send the request
        //       $result = curl_exec($ch);
        //       //Close request
        //       if ($result === FALSE) 
        //       {
        //         die('FCM Send Error: ' . curl_error($ch)); 
        //       }
        //       else
        //       {
        //         curl_close($ch);
        //       }
        //     }
        //     else
        //     { 
        //       $url = "https://fcm.googleapis.com/fcm/send";
        //       $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
        //       $message = 
        //         [ 
        //           "to" => $user->device_id,
        //           "priority" => 'high',
        //           "sound" => 'default', 
        //           "badge" => '1',
        //           "notification" =>
        //           [
        //             "title" => "Job assigned by admin",
        //             "body"  => "Job assigned by admin",
        //           ],
        //           "data"=>["notificationType" => "send notification to worker"]
                  
        //         ];
        //         $json = json_encode($message);
        //         $headers = array(
        //           'Content-Type: application/json',
        //           'Authorization: key='. $serverKey
        //         );
        //         $ch = curl_init();
        //         curl_setopt( $ch,CURLOPT_URL, $url);
        //         curl_setopt( $ch,CURLOPT_POST, true );
        //         curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        //         curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        //         curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        //         curl_setopt( $ch,CURLOPT_POSTFIELDS, $json);
        //         //Send the request
        //         $result = curl_exec($ch);
        //         //Close request
        //         if ($result === FALSE) 
        //         {
        //           die('FCM Send Error: ' . curl_error($ch));
        //         }
        //         else
        //         {
        //           curl_close($ch);
        //         }
        //     }    
        //   }
        //   return "success";
        // }
        // else
        // {
        //     return "error";
        // }
    }
}
