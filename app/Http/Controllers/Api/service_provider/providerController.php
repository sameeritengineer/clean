<?php
namespace App\Http\Controllers\Api\service_provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Response;
use Auth;
use URL;
use File;
use Mail;
use Imageresize;
use Image;
use Redirect;
use DateTime;
use Carbon\Carbon;
use App\User;
use App\ProviderProfile;
use App\Service;
use App\Servicetype;
use App\service_typesSpanish;
use App\Sptostype;
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
use App\Neighborhoods_Zipcodes;
use App\ProviderReview;
use App\TopRated_Provider;
use App\JObCheckinOut;

class providerController extends Controller
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

  //-------------------Service Provider Registration Request will send to admin to Get approvel-----------------------//

  public function providerRegister(Request $request)
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
              'service'    => 'required',
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
          $role                  = $input['role'];
          $remember_token        = \Hash::make(uniqid());
          $users                 = new User;
          $users->first_name     = $input['first_name'];
          $users->last_name      = $input['last_name'];
          $users->email          = $input['email'];
          $users->password       = Hash::make($input['password']);
          $users->mobile         = $input['mobile'];
          $users->device_id      = $input['device_id'];
          $users->device_type    = $input['device_type'];
          $users->services       = $input['service'];
          $users->working_status = 0;
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
              return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Thank you for registering! You will receive an email as soon as your profile has been approved!' , 'payload' => $record]);
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

    //--------------Provider Can Login only when Admin Approve Registration ------------//

	 public function providerLogin(Request $request)
   {
      $email = $request->get('email');
      $password = $request->get('password');
      $input = $request->all();
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
          $role_id = Role::where('name','provider')->first();
          $verifyUser = User::where('email',$email)->first();      
      		if($verifyUser != null )
          {
	        	$matchornot = Hash::check($request->password,$verifyUser->password);
  					if (($verifyUser->email == $email) && ($matchornot == true))
  					{ 
               $user_role = Userrole::where('user_id', $verifyUser->id)->where('role_id', $role_id->id)->first();
               if($user_role != null)
                {
    							if(($verifyUser->status == 1))
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
    							    return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "Your registration has not been approved by the site administration. Kindly wait till your registration gets approved"]);
    							}
                }
                else
                {
                    return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "Invalid credentials"]);
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

//------------------Otp will be send to provider mail in case provider forgot Password-------------------//

    public function forgotPassword(Request $request)
    {
        $users['email'] = $request->get('email');
        $input = array('email' => $users['email']);
        $rules = array(
            'email' => 'required|email',
        );

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
                          $otp->is_expired= date("Y-m-d H:i:s", strtotime("+10 minutes"));
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
                      return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> "Your registration has not been approved by the site administration. Kindly wait till your registration gets approved"]);
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

//------------------Match Otp in Mail .(Otp Get Expire in 10 mint)----------------//

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

//-------------------After OTP get Matched Provider Can Reset Password----------------------//

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
                  return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> "Your registration has not been approved by the site administration. Kindly wait till your registration gets approved"]);
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
//---------------------Change Service Provider password---------------------------//

  public function changePassword(Request $request)
  {
    $input = $request->all();
    $rules = array(
        'id'             => 'required',
        'old_password'   => 'required',
        'new_password'   => 'required',
       );

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
        if($user->status === 1)
        {
           $matchornot = Hash::check($request->old_password,$user->password);
           if($matchornot == true)
            {
              $change = User::find($request->id);
              $change->remember_token = bcrypt(time());
              $change->password = Hash::make($request->new_password);
              if($change->update())
              {
                  return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "Your password has been changed successfully! Thank you.","payload"=>$change]);
              } 
              else
              {
                  return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> "Oops, something went wrong."]);
              }
            }                   
            else
            {
                return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Old password doesnt match!"]);
            }
        }    
        else
        {
            return response()->json(['isSuccess' => false, 'isError' => true, 'message'=> "Your registration has not been approved by the site administration. Kindly wait till your registration gets approved"]);
        }
      }
      else
      {
        return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Invalid user"]);
      }
    }
  }
//-----------------------Add or Update Provider Profile image-------------------------------//

  public function insertUpdateProfilePic(Request $request)
  {
       $input = $request->all();
       $rules = array(
            'id'      => 'required',
            'image'   => 'required',
         );
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
              $providerprofile = ProviderProfile::where('serviceprovider_id',$user->id)->first();
              if($providerprofile != null)
              {                       
                 $thumbnailImagePath = public_path('profile/'.$providerprofile->image);
                 $image=$request->file('image');
                 $extension=$image->getClientOriginalExtension();
                 $filename=rand(100,999999).time().'.'.$extension;
                 $fullsize_image_path=public_path('profile/'.$filename);
                 Image::make($image)->save($fullsize_image_path);
                 $providerprofile->image  = $filename;
                 $providerprofile->status ='0';
                 File::delete($thumbnailImagePath);
                   if($providerprofile->update())
                   { 
                     return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "profile updated Succesfully"]);
                   }
                   else
                   {
                      return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Something Error. Try again!"]);
                   }      
              }
              else    
              {          
                  $add_new_profile = new ProviderProfile();
                  $add_new_profile->serviceprovider_id = $user->id;
                  $add_new_profile->status ='0';
                  $image=$request->file('image');
                  $extension=$image->getClientOriginalExtension();
                  $filename=rand(100,999999).time().'.'.$extension;
                  $fullsize_image_path=public_path('profile/'.$filename);
                  Image::make($image)->save($fullsize_image_path);
                  $add_new_profile->image = $filename;
                  
                  if($add_new_profile->save())
                  {
                      return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "profile added succesfully"]);
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

//-----------------------Display Provider Approved Profile Picture--------------------------------//


  public function DisplayProvideProfilePicture(Request $request)
  {
    $input = $request->all();
    $rules = array(
        'id'  => 'required',
     );
    $validator = Validator::make($input, $rules);
    if($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);
    }
    else
    {
      $user = \App\ProviderProfile::where('serviceprovider_id',$request->id)->first();
      if($user != null)
      {   
          if($user->image != null)
          {
            $baseUrl = URL::to('/');
            $user->image = $baseUrl.'/'.'profile/'.$user->image;
            return response()->json(['isSuccess' => true, 'isError' => false,'message' => "Success", 'payLoad'=>$user->image]); 
          }
          else
          {
            return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "There is no Profile Picture"]);
          }                                          
      }
      else
      {
        return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "User not found"]);
      }
    }
  }

//------------------------Update Full Service Provider Profile----------------------------//

  public function updateProviderProfile(Request $request)
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
        'service'    => 'required',
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
              $user->services        = $input['service'];
               
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

//----------------------Add or Update Service provider bio-----------------------------//

  public function addUpdateProviderBio(Request $request)
  {
     $input = $request->all();
     $rules = array(
            'id'              => 'required',
            'bio'             => 'required',
            'starttime'       => 'required',
            'endtime'         => 'required',    
            'servicetype'     => 'required',             
         );
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
            $providerid = Providerbio::where('serviceprovider_id', $user->id)->first();
            if($providerid != null)
            {
                $providerid->Bio = $input['bio'];
                $providerid->starttime = $input['starttime'];
                $providerid->endtime = $input['endtime'];
                $providerid->status = '0';
                if($providerid->update())
                {
                     Sptostype::where('serviceprovider_id',$user->id)->delete();
                     $servicetypes = $input['servicetype'];
                      foreach($servicetypes as $service)
                      {
                        foreach ($service as $key => $value)
                        {
                          Sptostype::create
                          ([
                              'serviceprovider_id'=>$user->id,
                              'service_id' => $user->services,
                              'servicetype_id' => $value
                          ]);
                        }  
                      }
                    return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "provider Bio updated Succesfully"]);
                }    
            }
            else
            {   
              $providerbio = new Providerbio;
              $providerbio->serviceprovider_id = $user->id;
              $providerbio->Bio = $input['bio'];
              $providerbio->starttime = $input['starttime'];
              $providerbio->endtime = $input['endtime'];
              $providerbio->status = '0';
              if($providerbio->save())
              {   
                  Sptostype::where('serviceprovider_id',$user->id)->delete();
                   $servicetypes = $input['servicetype'];
                   foreach($servicetypes as $servicetype)
                   {
                      foreach ($servicetype as $key => $value)
                      {
                         $Sptostype = new Sptostype;
                         $Sptostype->serviceprovider_id = $user->id;
                         $Sptostype->service_id = $user->services;
                         $Sptostype->servicetype_id = $value;
                         $Sptostype->save();  
                      }     
                   }
                return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "provider Bio Added Succesfully"]);
              }
            }    
          }
          else
          {
            return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "User not found"]);
          }
      }
    }

//----------------------Display Approved Service provider Bio-----------------------------//

  public function displayApprovedProviderBio(Request $request)
  {
     $input = $request->all();
     $rules = array
     (
       'id' => 'required',             
     );
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
            $providerid =  Approved_Bio::where('serviceprovider_id', $user->id)->first();
            if($providerid != null)
            {
              $getservicetypes = Sptostype::where('serviceprovider_id',$user->id)->get();
              $stypenames= array();            
              foreach($getservicetypes as $getservicetype)
              {
                $stypenames[] = Servicetype::where('id', $getservicetype->servicetype_id)->first(['id','name']);      
              }
                $record = array('user_id' => $user->id,'bio'  => $providerid->Bio, 'starttime'  => $providerid->starttime, 'endtime'  => $providerid->endtime, 'servicetypes' => $stypenames); 
                return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "Approved provider Bio",'payLoad'=>$record]);       
            }
            else
            {
               return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Providr Bio Either Empty or not Approved"]);
            }
        }
        else
        {
           return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "User not found"]);
        }
      }
   }
//----------------------Active/Deactive Service Provider-----------------------------//

  public function activedeactiveseriveprovider(Request $request)
  {
     $input = $request->all();
     $rules = array(
            'id'        => 'required',             
         );
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
          if($user->status == 1 && $user->working_status == 1)
          {
            $user->working_status = 0;
            $user->update();
            return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "Service Provider is De-Active" , 'working_status' => $user->working_status]); 
          }
          elseif ($user->status == 1 && $user->working_status == 0) 
          {
            $user->working_status = 1;
            $user->update();
            return response()->json(['isSuccess' => true, 'isError' => false, 'message' => "Service Provider is Active" , 'working_status' => $user->working_status]); 
          } 
          else
          {
             return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Service Provider Declined or Not Approved "]);
          }
        }
        else
        {
           return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "User not found"]);
        }
      }
   } 

//----------------------------Get State List---------------------------------//

  public function getStateList(Request $request)
  {
      $countryId = 6;
      $states = State::where('country_id',$countryId)->get(['id','name','country_id']);
      if(count($states)>0)
      {
        	return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all States","payload"=> $states]);
      }
      else
      {
        	return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no state"]);
      }
  }

//-------------------------------Get City List----------------------------------------------//

  public function getCityList(Request $request)
  {
      $state_id = $request->get('state_id');
      $input = $request->all();
      $rules = array(
          'state_id' => 'required',
          );
      $validator = Validator::make($input, $rules);
      if($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);
      }
      else
      {
          $cities = City::where('state_id',$state_id)->get(['id','name','state_id']);
          if(count($cities)>0)
          {
             return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all Cities","payload"=> $cities]);
          }
          else
          {
              return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no City"]);
          }
      }
  }

//-------------------------------Get Zipcode List----------------------------------------------//
    
  public function getZipcodeList(Request $request)
  {
    $city_id = $request->get('city_id');
    $input = $request->all();
    $rules = array(
      'city_id' => 'required',
      );
    $validator = Validator::make($input, $rules);
    if($validator->fails()) 
    {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);
    }
    else
    {
      $zipcodes = Zipcode::where('city_id',$city_id)->get(['id','zipcode','city_id']);
      if(count($zipcodes)>0)
      {
         return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "List of all Zipcodes","payload"=> $zipcodes]);
      }
      else
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => "There is no Zipcode"]);
      }
    }
  }



 //------------------------------Store All state together ----------------------------------------------//

  public function addallstate(Request $request)
  {
     $input = $request->all();
     $rules = array(
        'cuntry_id' => 'required',
        'state_id' => 'required',
      );
     $validator = Validator::make($input, $rules);
     if($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);
      }
      else
      {
        $contryid =$input['cuntry_id'];
        $stateslists = $input['state_id'];
        foreach($stateslists as $state)
        {
          foreach ($state as $key => $value)
          {
            $addState = new State;
            $addState->country_id = $contryid;
            $addState->name = $value;
            $addState->save();  
          }  
        }
          return response()->json(['isSuccess' => true, 'isError' => false, 'message'=> "added all states"]);
      }
  }

  public function ProviderfullDetails(Request $request)
  {
     $input = $request->all();
     $rules = array(
            'id'        => 'required',             
         );
      $validator = Validator::make($input, $rules);
      if($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);   
      }
      else
      {     
          $user   = User::where('id' , $input['id'])->first(['id','first_name','last_name','email','services','mobile','status','working_status','image']);
          if($user->image != null)
          {
            $baseUrl = URL::to('/');
            $user->image = $baseUrl.'/'.'profile/'.$user->image;
          }
           else
          {
             $user->image = "There is no Profile Picture";
          }
         if($user != null)
         { 
            $service_name = Service::where('id' , $user->services)->first();
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
                        $record = array('UserDetails' => $user ,'Service_name' =>  $service_name->name,'Address' => $providerfulldetails->address ,'State_id' => $providerfulldetails->state,'State' => $state_name->name,'City_id' => $providerfulldetails->city,'Cityname' => $city_name->name,'Zipcode_id' => $providerfulldetails->zipCode,'Zipcode' => $Zipcode->zipcode); 
                          return response()->json(['isSuccess' => true, 'isError' => false, 'message' => 'provider Full Details.','payLoad'=>$record]);   
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
  
  public function acceptInstantJob(Request $request)
  {
      $input = $request->all();
      $rules = array(
        'job_id' => 'required',
        'provider_id' => 'required',
      );
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {
          $check_job_status = Instant_schedule_job::where('job_id', $input['job_id'])->first();
          if(empty($check_job_status))
          {
              $save_Customer_id = InstantBooking::find($input['job_id']);
              $Instant_job = new Instant_schedule_job;
              $Instant_job->job_id  = $input['job_id'];
              $Instant_job->provider_id  = $input['provider_id'];
              $Instant_job->cutomer_id  = $save_Customer_id->cutomer_id;
              $Instant_job->status = '0';
              if($Instant_job->save())
              {
                  $Get_customer_id = InstantBooking::find($input['job_id']);
                  if(!empty($Get_customer_id))
                  { 

                      //----------servicename for notification data---------//
                            $service_name = $Get_customer_id->Services;
                            $service_name_aary  = explode(',',$service_name);
                            $nameArray = array();
                            foreach ($service_name_aary as $value) 
                            {
                              $service = Servicetype::where('id', $value)->first();     
                              array_push($nameArray, $service->name);
                            }
                            $service_string = implode(',', $nameArray);

                    $notify_customer = User::find($Get_customer_id->cutomer_id);
                    if($notify_customer->device_type == "A")
                    {
                      $url = "https://fcm.googleapis.com/fcm/send";
                      $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
                      $message = 
                      [ 
                        "to" => $notify_customer->device_id,
                        "data" => 
                            [
                              "title"        => "Booking Confirmed",
                              "provider_id"  => $Instant_job->provider_id,
                              "Service"      => $service_string,
                              "address"      => $Get_customer_id->customer_address.",zipcode ".$Get_customer_id->zipcode,
                              "Date"         => $Get_customer_id->date ,
                              "Time"         => $Get_customer_id->time,
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
                            "to" => $notify_customer->device_id,
                            "priority" => 'high',
                            "sound" => 'default', 
                            "badge" => '1',
                            "notification" =>
                                [
                                  "title" => "Booking Confirmed",
                                  "body" => $service_string,
                                ],
                              "data" => 
                                [ 
                                  "notificationType" => "Booking_Confirmed",
                                  "paylod" =>[
                                      "title"        => "Booking Confirmed",
                                      "provider_id"  => $Instant_job->provider_id,
                                      "Service"      => $service_string,
                                      "address"      => $Get_customer_id->customer_address.",zipcode ".$Get_customer_id->zipcode,
                                      "Date"         => $Get_customer_id->date ,
                                      "Time"         => $Get_customer_id->time,
                                     ]
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
                      curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
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

                    return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Success', 'Provider_id' => $Instant_job->provider_id]);
                  }
                  else
                  {
                     return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Something Went Wrong ']);
                  }  
              }
          }
          else
          {
               return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'Sorry this job already accepted by Someone']);
          }
      }
    }

   //--------List Of Current Jobs of a particular Service Provider-------------//

   public function ListOfCurrentJobsForProvider(Request $request)
   {
      $input = $request->all();
      $lang  = $request->lang;
      $rules = array('provider_id' => 'required',);
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {   
        $current_jobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
        ->where('instant_schedule_jobs.provider_id',$request->provider_id)
        ->where('instant_schedule_jobs.status', '0')
        ->join('users', 'users.id', '=', 'instant_bookings.cutomer_id')
        ->select('users.id as customer_id','users.first_name as customer_name','users.image as customer_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address','instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time')
        ->get();

          foreach($current_jobs as $data)
          {
             $dataaaa = array();
             $idsss = explode(',',$data->Services_names);
             for($i=0;$i<count($idsss);$i++)
             {
               $getid =  $idsss[$i];
               if($lang == 'es')
               {
                  $service = service_typesSpanish::where('servicetype_id',$getid)->first();
               }
               else
               {
                  $service = Servicetype::where('id',$getid)->first();
               }
               array_push($dataaaa, $service->name);
             }
             if($lang == 'es')
             {
                $service_string = implode(' y ', $dataaaa);
             }
             else
             {
                $service_string = implode(' and ', $dataaaa);
             } 
             $data->Services_names = $service_string;
             $data->customer_profile = URL::to('/').'/'.'profile/'.$data->customer_profile;
          }

          if(count($current_jobs)> 0)
          {  
            return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'List Of Current Jobs.', 'payload' => $current_jobs]);
          }
          else
          {
            return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Sorry, No Current Jobs Available At This Time."]);
          }
      }
    }

    //--------List Of Completed Jobs-------------//

   public function ListOfCompletedJobsForProvider(Request $request)
   {
      $input = $request->all();
      $lang  = $request->lang;
      $rules = array('provider_id' => 'required',);
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {   
          $CompletedJobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
          ->where('instant_schedule_jobs.provider_id',$request->provider_id)
          ->where('instant_schedule_jobs.status', '1')
          ->join('users', 'users.id', '=', 'instant_bookings.cutomer_id')
          ->select('users.id as customer_id','users.first_name as customer_name','users.image as customer_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address','instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time')
          ->get();

          foreach($CompletedJobs as $data)
          {
             $dataaaa = array();
             $idsss = explode(',',$data->Services_names);
             for($i=0;$i<count($idsss);$i++)
             {
               $getid =  $idsss[$i];
               if($lang == 'es')
               {
                  $service = service_typesSpanish::where('servicetype_id',$getid)->first();
               }
               else
               {
                  $service = Servicetype::where('id',$getid)->first();
               }
               array_push($dataaaa, $service->name);
             }
             if($lang == 'es')
             {
                $service_string = implode(' y ', $dataaaa);
             }
             else
             {
                $service_string = implode(' and ', $dataaaa);
             }  
             $data->Services_names = $service_string;
             $data->customer_profile = URL::to('/').'/'.'profile/'.$data->customer_profile;
          }

          if(count($CompletedJobs)> 0)
          {  
              return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'List Of Completed Jobs.', 'payload' => $CompletedJobs]);
          }
          else
          {
            return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Sorry, No Completed Jobs Available At This Time."]);
          }
      }
    }


    //--------List Of Canceled Jobs which canceled by provider-------------//

   public function ListOfCanceledJobsFromProvider(Request $request)
   {
      $input = $request->all();
      $lang  = $request->lang;
      $rules = array('provider_id' => 'required',);
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {
         $CanceledJobs = Instant_schedule_job::join('instant_bookings','instant_bookings.id','=','instant_schedule_jobs.job_id')
          ->where('instant_schedule_jobs.provider_id',$request->provider_id)
          ->where('instant_schedule_jobs.status', '2')
          ->join('users', 'users.id', '=', 'instant_bookings.cutomer_id')
          ->select('users.id as customer_id','users.first_name as customer_name','users.image as customer_profile','instant_bookings.id as job_id','instant_bookings.zipcode as zipcode','instant_bookings.customer_address as customer_address','instant_bookings.Services as Services_names','instant_bookings.date as date','instant_bookings.time as time')
          ->get();

          foreach($CanceledJobs as $data)
          {
             $dataaaa = array();
             $idsss = explode(',',$data->Services_names);
             for($i=0;$i<count($idsss);$i++)
             {
               $getid =  $idsss[$i];
               if($lang == 'es')
               {
                  $service = service_typesSpanish::where('servicetype_id',$getid)->first();
               }
               else
               {
                  $service = Servicetype::where('id',$getid)->first();
               }
               array_push($dataaaa, $service->name);
             }
             if($lang == 'es')
             {
                $service_string = implode(' y ', $dataaaa);
             }
             else
             {
                $service_string = implode(' and ', $dataaaa);
             }  
             $data->Services_names = $service_string;
             $data->customer_profile = URL::to('/').'/'.'profile/'.$data->customer_profile;
          }
          if(count($CanceledJobs)> 0)
          {  
              return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'List Of Canceled Jobs.', 'payload' => $CanceledJobs]);
          }
          else
          {
            return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Sorry, No Canceled Jobs Available At This Time."]);
          }
      }
    }

    //--------Cancel Current Job("Here job will Canceled by service Provider")-------------//

    public function CanceljobByProvider(Request $request)
    {
      $input = $request->all();
      $rules = array('job_id' => 'required','provider_id' => 'required',);
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {
          $CancelJob = Instant_schedule_job::where('job_id', $request->job_id)->where('provider_id' , $request->provider_id)->first();  
          $CancelJob->status = '2'; 
          if($CancelJob->update())
          {
              $customer = User::where('id' , $CancelJob->cutomer_id)->first();
              if($customer->device_type == "A")
              {
                  $url = "https://fcm.googleapis.com/fcm/send";
                  $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
                  $message = 
                    [ 
                      "to" => $customer->device_id,
                      "data" => 
                          [
                            "title"        => "Job Canceled By Provider",
                            "job_id"       => $CancelJob->job_id,     
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
                      "to" => $customer->device_id,
                      "priority" => 'high',
                      "sound" => 'default', 
                      "badge" => '1',
                      "notification" =>
                        [
                            "title" => "Job Canceled",
                            "body" => "Job Canceled",
                        ],
                      "data" => 
                        [ 
                          "notificationType" => "JobCanceled",
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
            return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Job cancelled Successfully']);
          }
          else
          {
             return response()->json(['isSuccess' => false, 'isError' => true, 'message' => "Something Went Wrong"]);
          }
      }
    }

    public function showproviderReferralcode(Request $request)
    {
      $input = $request->all();
      $rules = array(
          'provider_id' => 'required',
      );
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {  
          $Referral  = User::where('id' , $input['provider_id'])->first();
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

    public function checkInForJob(Request $request)
    {
      $input = $request->all();
      $rules = array(
          'provider_id' => 'required',
          'Job_id' => 'required',
          'date' => 'required',
          'time' => 'required',
      );
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {
        $Is_Ckeckin = JObCheckinOut::where('job_id', $input['Job_id'])->first();
        if(empty($Is_Ckeckin))
        {
          $Addcheckindetails = new  JObCheckinOut;
          $Addcheckindetails->job_id = $input['Job_id'];
          $Addcheckindetails->checkIn = $input['time'];
          if($Addcheckindetails->save())
          {   
              $Check_in = Instant_schedule_job::where('job_id', $input['Job_id'])->where('provider_id' , $input['provider_id'])->first();
              $customer = User::where('id' ,  $Check_in->cutomer_id)->first();
              if($customer->device_type == "A")
              {
                  $url = "https://fcm.googleapis.com/fcm/send";
                  $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
                  $message = 
                    [ 
                      "to" => $customer->device_id,
                      "notification" => 
                          [
                            "title"        => "Job Check In",                        
                          ],
                       "data" => 
                        [ 
                          "notificationType" => "CheckIn",
                          "payload" =>[
                            "title"            => "Job Started",
                            "Date"             => $input['date'],
                            "Time"             => $input['time'],
                          ]
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
                      "to" => $customer->device_id,
                      "priority" => 'high',
                      "sound" => 'default', 
                      "badge" => '1',
                      "notification" =>
                        [
                            "title" => "Job Check In", 
                        ],
                      "data" => 
                        [ 
                          "notificationType" => "CheckIn",
                          "payload" =>[
                            "title"            => "Job Started",
                            "Date"             => $input['date'],
                            "Time"             => $input['time'],
                          ]
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
               return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Job Started Successfully']); 
          }
          else
          {
              return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'something went wrong']);
          }
        }
        else
        {   
          $record = array('CheckIn Time' => $Is_Ckeckin->checkIn);  
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'You have already Checked in For this job','payload' => $record]);
        }
      }  
    }
    

    public function checkOutFromJob(Request $request)
    {
      $input = $request->all();
      $rules = array(
          'provider_id' => 'required',
          'Job_id' => 'required',
          'date' => 'required',
          'time' => 'required',
      );
      $validator = Validator::make($input, $rules);
      if ($validator->fails()) 
      {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $this->formatErrors($validator->errors()->getMessages())]);
      }
      else 
      {  
          $AddcheckOutdetails = JObCheckinOut::where('job_id', $input['Job_id'])->first();
          $AddcheckOutdetails->checkOut = $input['time'];
          if($AddcheckOutdetails->save())
          {   
              $check_in  = new DateTime($AddcheckOutdetails->checkIn);
              $check_out = new DateTime($AddcheckOutdetails->checkOut);
              $interval  = $check_in->diff($check_out);
              $jobTime   =  $interval->h.':'.$interval->i.':'.$interval->s;
              $Check_out = Instant_schedule_job::where('job_id', $input['Job_id'])->where('provider_id' , $input['provider_id'])->first(); 
              $Check_out->status = '1'; 
               if($Check_out->update())
               {
                    $customer = User::where('id' ,  $Check_out->cutomer_id)->first();
                    if($customer->device_type == "A")
                    {
                        $url = "https://fcm.googleapis.com/fcm/send";
                        $serverKey = 'AAAA1zGBIz8:APA91bFJCHqSlvOpugSxb5_Hxiq8yWmpplNACcVei7ceXWG0lcVrsXVtC_wGeumNLXdtgrL4oGHvmEqRBMFz1MmYKotTM508S2ueNRv2lzTdggR1qVbmT4sMzlAVGezKhmKVREfzZ_EZ'; 
                        $message = 
                          [ 
                            "to" => $customer->device_id,
                            "notification" => 
                                [
                                  "title"        => "Job Check Out",                        
                                ],
                             "data" => 
                              [ 
                                "notificationType" => "CheckOut",
                                "payload" =>[
                                  "title"            => "Job Completed",
                                  "Date"             => $input['date'],
                                  "Time"             => $input['time'],
                                ]
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
                            "to" => $customer->device_id,
                            "priority" => 'high',
                            "sound" => 'default', 
                            "badge" => '1',
                            "notification" =>
                              [
                                  "title" => "Job Check Out", 
                              ],
                            "data" => 
                              [  
                                "notificationType" => "CheckOut",
                                "payload" =>[
                                  "title"            => "Job Completed",
                                  "Date"             => $input['date'],
                                  "Time"             => $input['time'],
                                  "JobTime"          => $jobTime,
                                ]
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
                       return Response::json(['isSuccess' => true, 'isError' => false, 'message' => 'Job Completed Successfully']);
               }
               else
               {
                   return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'something went wrong']);
               }
          }
          else
          {
              return Response::json(['isSuccess' => false, 'isError' => true, 'message' => 'something went wrong']);
          }

      } 
    }

    public function provider_logout(Request $request)
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

    public function save_lat_long(Request $request)
    {
      $input = $request->all();
      $rules = array(
        'provider_id'      => 'required',
        'lat'   => 'required',
        'long'=>'required'
       );
      $validator = Validator::make($input, $rules);
      if($validator->fails()) 
      {
        return Response::json(['isSuccess' => false, 'isError' => true, 'message' => $validator->errors()->first()]);
      }
      else
      {
        $provider = new \App\ProviderLocation;
        $provider->provider_id = $request->provider_id;
        $provider->lat = $request->lat;
        $provider->long = $request->long;
        if($provider->save())
        {
          return Response::json(['isSuccess' => true, 'isError' => false, 'message'=>'Provider location has been saved!.','payload'=>$provider]);
        }
        else
        {
          return Response::json(['isSuccess' => false, 'isError' => true, 'message'=>'Something error while saving your location!.']);
        }
      }
    }
}

