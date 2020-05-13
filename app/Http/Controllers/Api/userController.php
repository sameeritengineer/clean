<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\User;
use App\Useraddress;
use App\Otp;
use App\Role;
use App\Userrole;
use App\Country;
use App\City;
use App\State;
use App\Zipcode;
use Response;
use Auth;
use Mail;
use File;
use Imageresize;
use Image;
use URL;
use App\ProviderLocation;

class userController extends Controller
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

    public function isUserExist($email)
	{
		$userCheck=User::where('email',$email)->first();
		if(!$userCheck)
		{
			return true;
		}
		else
        {
			return false;
		}
	}
    public function genRandomString(){
      $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";$res = "";for ($i = 0; $i < 10; $i++){$res .= $chars[mt_rand(0, strlen($chars)-1)];}return $res;
    }

    public function userRegister(Request $request)
    {
        $input = $request->all();
        $rules = array(
                    'first_name' => 'required|max:255|min:2',
                    'email'      => 'required|email',
                    'password'   => 'required|min:8',
                    'mobile'     => 'required',
                    'device_id'  => 'required',
                    'device_type'=> 'required',
                    'role'       => 'required',
                    'address'    => 'required',
                    'state'      => 'required',
                    'city'       => 'required',
                    'zipCode'    => 'required',    
                );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) 
        {
            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
        }
        else 
        {
            if(self::isUserExist($request->email))
            {   
                $role = $input['role'];
                $remember_token         = \Hash::make(uniqid());
                $users                  = new User;
                $users->first_name      = $input['first_name'];
                $users->last_name       = $input['last_name'] ?? "";
                $users->email           = $input['email'];
                $users->password        = Hash::make($input['password']);
                $users->mobile          = $input['mobile'];
                //$users->ipaddress       = $request->ip();
                $users->device_id       = $input['device_id'];
                $users->device_type     = $input['device_type'];
                $users->working_status  = 0;
                if ($role== "cu") {$users->status = 1;}
                    elseif ($role== "pr"){$users->status = 0;} 
                    else { return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Invalid Role']);}
                $users->remember_token  = $remember_token;
                $users->referral_code  =  $this->genRandomString(uniqid());

                if($users->save())
                {   
                    if ($role== "cu") {$userrole="customer";}
                    elseif ($role== "pr"){$userrole="provider";} 
                    else { return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Invalid Role']);}

                    $role_id = Role::where('name',$userrole)->first();
                    $add_userroles = new Userrole;
                    $add_userroles->user_id = $users->id;
                    $add_userroles->role_id = $role_id->id;
                    if($add_userroles->save())
                    {
                        $userAddress = new Useraddress;
                        $userAddress->userId = $users->id;
                        $userAddress->country = 6;
                        $userAddress->address = $input['address'];
                        $userAddress->state = $input['state'];
                        $userAddress->city = $input['city'];
                        $userAddress->zipCode = $input['zipCode'];

                        if($userAddress->save())
                        {
                         $record = array('user_id' => $users->id, 'access_token' => $remember_token);
                         return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Thank you for registering.' , 'payload' => $record]);
                        }
                         else
                        {
                           return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Went Wrong ']);
                        }
                    }
                    else
                    {

                        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Wrong with Registration']);
                    }                   
                }
                else
                {
                    return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Wrong with Registration']);
                }
            }
            else
            {
                return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Email already exist']);
            }
        }
    }

    public function userlogin(Request $request)
    { 
        $email      = $request->get('email');
        $password   = $request->get('password');
        $input      = $request->all();
        $rules = array(
            'email' => 'required|email',
            'password' => 'required',
            'device_id' => 'required'
            );
        $validator = Validator::make($input, $rules);
        if($validator->fails()) 
        {
            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);       
        }
        else
        {   
            $role_id = Role::where('name','customer')->first();
            $verifyUser = User::where('email',$email)->first();
            if($verifyUser != null )
            {
                $matchornot = Hash::check($password,$verifyUser->password);
                if (($verifyUser->email == $email) && ($matchornot == true))
                {
                   $user_role = Userrole::where('user_id', $verifyUser->id)->where('role_id', $role_id->id)->first();
                   if($user_role != null)
                   { 
                      $device_token = User::where('id' ,$verifyUser->id)->first();
                      $device_token->device_id = $input['device_id'];
                      if($device_token->update())
                      {
                        $data = array('user_id' => $verifyUser->id, 'username' => $verifyUser->first_name, 'access_token' => $verifyUser->remember_token);
                        return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Login Success, Welcome to Cleanerup', 'payload' => $data]); 
                      }                         
                   }
                   else
                   {
                     return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "Invalid User"]);
                   }          
                }
                else
                {
                    return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "Invalid Password"]);
                }
            }
            else
            {
                return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "Invalid User"]);
            }
        }
    }

    public function forgotPassword(Request $request)
    {
        $users['email'] = $request->get('email');
        $input = array('email' => $users['email']);
        $rules = array('email' => 'required|email',);
        $validator = Validator::make($input, $rules);
        if ($validator->fails())
        {
            return Response::json(['status' => 0, 'message' => $validator->errors()->first()]); 
        } 
        else
        {
            $checkExistance=self::isUserExist($request->email);
            if(!$checkExistance)
            {
                $user = User::where('email',$request->email)->first();
                if($user != null)
                {               
                    if($user->status === 1)
                    {
                        $otp = Otp::where('user_id',$user->id)->first();
                        if(!empty($otp))
                        {
                            $otp->user_id = $user->id;
                            $otp->otp = mt_rand(1000, 9999);
                            $otp->is_expired= date("Y-m-d H:i:s", strtotime("+10 minutes"));
                           
                            if($otp->update())
                            {
                               $data = array('email' => $input['email'],);
                                Mail::send('emails.sendOtp',$data, function($message) use ($data)
                                {
                                    $message->from(config('mail.username'));
                                    $message->to($data['email']);
                                    $message->subject('Email Verification');
                                });
                                return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "Otp send to your email" ,"payLoad" => $user]);
                            }
                        }                               
                        else
                        {
                            $otp = new Otp();
                            $otp->user_id = $user->id;
                            $otp->otp = mt_rand(1000, 9999);
                            $otp->is_expired= date("Y-m-d H:i:s", strtotime("+2 minutes"));
                            if($otp->save())
                            {
                                $data = array('email' => $input['email']);
                                Mail::send('emails.sendOtp',$data, function($message) use ($data)
                                {
                                    $message->from(config('mail.username'));
                                    $message->to($data['email']);
                                    $message->subject('Email Verification');
                                });
                                return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "Otp send to your email"]);
                            }
                        }                                                           
                    }
                    else
                    {
                        return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> "You'r not able to login"]);
                    }
                }
                else
                {
                    return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> "User not found"]);
                }
            }
            else
            {
                return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> 'Incorrect credentials']);
            }
        }  
    }

    public function matchOtp(Request $request)
    {
        if (!empty($request->userId) && (!empty($request->otp))) 
        {
            $match = Otp::where('user_id',$request->userId)->first();
            if($match != null)
            {
                $expired = Carbon::parse($match->is_expired);
                $date = Carbon::parse(Carbon::now()->toDateTimeString());
                if($match->otp == $request->otp)
                {
                    if($date >= $expired)
                    {
                        return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "OTP Expired"]);
                    }
                    else
                    {
                       return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "OTP Matched"]); 
                    }
                }                   
                else
                {
                    return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Invalid OTP"]);
                }           
            }
            else
            {
                return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Invalid User"]);
            }
        }
        else
        {
            return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "All fields are required"]);
        }
    }

    public function resetPassword(Request $request)
    {
        if(!empty($request->email) && !empty($request->password))
        {
            $checkExistance=self::isUserExist($request->email);
            if(!$checkExistance)
            {
                $user = User::where('email',$request->email)->first();
                if($user != null)
                {           
                    if($user->status === 1)
                    {
                        $reset = User::find($user->id);
                        $reset->remember_token = bcrypt(time());
                        $reset->password = Hash::make($request->password);
                        if($reset->update())
                        {
                            return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "Sucessfully reset password","payload"=>$reset]);
                        }                       
                    }
                    else
                    {
                        return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> "You'r not able to login"]);
                    }  
                }
                else
                {
                    return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> "User not found"]);
                }
            }
            else
            {
                return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> 'Incorrect credentials']);
            }
        }
        else
        {
            return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "All fields are required"]);
        }
    }

  
  
    // {
    //     $input = $request->all();
    //     $rules = array(
    //         'id'             => 'required',
    //         'old_password'   => 'required',
    //         'new_password'   => 'required',
    //        );

    //     $validator = Validator::make($input, $rules);
    //     if($validator->fails()) 
    //     {
    //         return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);    
    //     }
    //     else
    //     {
    //         $user = User::find($request->id);
    //         if($user != null)
    //         { 
    //           if($user->status === 1)
    //             {
    //                $matchornot = Hash::check($request->old_password,$user->password);
    //                if($matchornot == true)
    //                 {
    //                     $change = User::find($request->id);
    //                     $change->remember_token = bcrypt(time());
    //                     $change->password = Hash::make($request->new_password);
    //                     if($change->update())
    //                     {
    //                         return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "Your password has been changed successfully! Thank you.","payload"=>$change]);
    //                     } 
    //                     else
    //                     {
    //                         return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> "Oops, something went wrong."]);
    //                     }
    //                 }                   
    //                 else
    //                 {
    //                     return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Old password doesnt match!"]);
    //                 }
    //             }    
    //             else
    //             {
    //                 return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> "Your registration has not been approved by the site administration. Kindly wait till your registration gets approved"]);
    //             }
    //         }
    //         else
    //         {
    //           return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Invalid user"]);
    //         }
    //     }
    // }

    public function userProfilePic(Request $request)
    {
        $input = $request->all();
        $rules = array('id' => 'required', 'image'   => 'required',);
        $validator = Validator::make($input, $rules);
        if($validator->fails()) 
        {
            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);  
        }
        else
        {
           $user = User::find($request->id);
           if($user != null)
           {  
              $thumbnailImagePath = public_path('profile/'.$user->image);
              if(File::exists($thumbnailImagePath))
              {
                $image=$request->file('image');
                $extension=$image->getClientOriginalExtension();
                $filename=rand(100,999999).time().'.'.$extension;
                $fullsize_image_path=public_path('profile/'.$filename);
                Image::make($image)->save($fullsize_image_path);
                $user->image = $filename;
                File::delete($thumbnailImagePath);
                if($user->update())
                {
                    $baseUrl = URL::to('/');
                    $user->image = $baseUrl.'/'.'profile/'.$user->image;
                    return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "profile updated Successfully", 'payLoad'=>$user->image]);
                }
                else
                {
                    return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Something Error. Try again!"]);
                }
              } 
              else
              {
                $image=$request->file('image');
                $extension=$image->getClientOriginalExtension();
                $filename=rand(100,999999).time().'.'.$extension;
                $fullsize_image_path=public_path('profile/'.$filename);
                Image::make($image)->save($fullsize_image_path);
                $user->image = $filename;
                if($user->save())
                  {
                    $baseUrl = URL::to('/');
                    $user->image = $baseUrl.'/'.'profile/'.$user->image;
                    return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "profile added Successfully", 'payLoad'=>$user->image]);
                  }
                  else
                  {
                    return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Something Error. Try again!"]);
                  }
              }                      
           }
           else
           {
            return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "User not found"]);
           }
        }
    }

    public function customerfullDetails(Request $request)
    {
        $input = $request->all();
        $rules = array(
                'id'        => 'required',);
        $validator = Validator::make($input, $rules);
        if($validator->fails()) 
        {
              return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);   
        }
        else
        {     
           $user   = User::where('id' , $input['id'])->first(['id','first_name','last_name','email','mobile','image']);
           if($user->image != null)
           {
            $baseUrl = URL::to('/');
            $user->image = $baseUrl.'/'.'profile/'.$user->image;
           }
           else
           {
             $user->image = "No Image Found";
           }
           if($user != null)
           {
                $providerfulldetails = Useraddress::where('userId', $user->id)->first();
                if($providerfulldetails != null)
                {
                    $state_name = State::where('id', $providerfulldetails->state)->first();
                    if($state_name != null)
                    {
                        $city_name = City::where('id',$providerfulldetails->city)->first();
                        if($city_name != null)
                        {
                            $Zipcode = Zipcode::where('id',$providerfulldetails->zipCode)->first();
                            if($Zipcode != null)
                            {
                                $record = array('UserDetails' => $user ,'Address' => $providerfulldetails->address ,'State_id' => $providerfulldetails->state,'State' => $state_name->name,'City_id' => $providerfulldetails->city,'Cityname' => $city_name->name,'Zipcode_id' => $providerfulldetails->zipCode,'Zipcode' => $Zipcode->zipcode); 
                                return response()->json(['isSuccess' => true, 'isError' => false, 'message' => 'customer Full Details.','payLoad'=>$record]);   
                            }
                            else
                            {
                                return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Zipcode Missing ']);
                            }
                        }
                        else
                        {
                            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'City Missing ']);
                        }
                    }
                    else
                    {
                        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'State Missing ']);
                    }
                }
                else
                {
                    return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Went Wrong ']);
                }
            }
            else
            {
              return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "User not found"]);
            }
        }
    }

    public function updateCustomerProfile(Request $request)
    {
      $input = $request->all();
      $rules = array(
          'id'         => 'required',
          'first_name' => 'required|max:255|min:2',                
          'mobile'     => 'required',
          'address'    => 'required',
          'state'      => 'required',
          'city'       => 'required',
          'zipCode'    => 'required',
      );
      $validator = Validator::make($input, $rules);

      if ($validator->fails()) {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
       else 
      {    
          $user   = User::find($input['id']);
          if($user != null)
          {
              $user->first_name      = $input['first_name'];
              $user->last_name       = $input['last_name'];
              $user->mobile          = $input['mobile'];   
             if($user->update())
              { 
                $userAddress = Useraddress::where('userId',$user->id)->first();
                $userAddress->country = 6;
                $userAddress->address = $input['address'];
                $userAddress->state = $input['state'];
                $userAddress->city = $input['city'];
                $userAddress->zipCode = $input['zipCode'];

                if($userAddress->update())
                {
                $record = array('user_id' => $user->id,);   
                return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Your profile has been updated successfully.' , 'payload' => $record]);
                }
                 else
                {
                   return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Went Wrong ']);
                }
              }
              else
              {
                  return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Wrong with Registration']);
              }   
        }
        else
        {
          return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "User not found"]);
        }
      }
    }

    public function showReferralcode(Request $request)
    {
      $input = $request->all();
      $rules = array(
          'Customer_id' => 'required',
      );
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {  
          $Referral  = User::where('id' , $input['Customer_id'])->first();
          if($Referral != null)
          {
             return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Referral code.' , 'payload' => $Referral->referral_code]);
          }
          else
          {
             return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Referral code Missing']);
          }
      }  
    }
    public function logout(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        $user->device_id = null;
        if($user->update())
        {
           return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'you have successfully logged out']);
        }
        else
        {
            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Went Wrong ']);
        }   
    }

    public function add_provider_location(Request $request)
    {
        if(isset($request->location_id) && ($request->location_id != 0))
        {
            $location = ProviderLocation::find($request->location_id);
        }
        else
        {
            $location = new ProviderLocation;
        }
        $location->provider_id = $request->provider_id;
        $location->location = $request->location;
        if($location->save())
        {
            return response()->json(['status'=>true,'message'=>'Thanks for sharing your current location.','location_id'=>$location->id]);
        }
        else
        {
            return response()->json(['status'=>false,'message'=>'Something error,while sharing your location.']);
        }
    }

public function save_chat(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'user_id'      => 'required',
            'provider_id'   => 'required',
            'message' => 'required',
'type'=>'required'
         );
        $validator = Validator::make($input, $rules);
        if($validator->fails()) 
        {
            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);
        }
        else
        {
            $chat = new \App\Chat;
            $chat->user_id = $input['user_id'];
            $chat->provider_id = $input['provider_id'];
            $chat->message = $input['message'];
$chat->type = $input['type'];
            if($chat->save())
            {
                return response::json(['isSuccess' => true, 'isError' => false,'payload'=>$chat,'message'=>'message sent!.']);
            }
            else
            {
                return response::json(['isSuccess' => false, 'isError' => true,'message'=>'Something error while sending your message!.']); 
            }
        }
    }
public function get_all_chat_message(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'user_id'      => 'required',
            'provider_id'   => 'required',
         );
        $validator = Validator::make($input, $rules);
        if($validator->fails()) 
        {
            return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);
        }
        else
        {
            $chats = \App\Chat::where('user_id',$input['user_id'])->where('provider_id',$input['provider_id'])->orderBy('id','desc')->get();
            if(count($chats)>0)
            {
                return Response::json(['isSuccess' => true, 'isError' => false, 'message'=>'List of all chats.','payload'=>$chats]);
            }
            else
            {
                return Response::json(['isSuccess' => false, 'isError' => true, 'message'=>'Not Found!.']);
            }
        }
    }
}


