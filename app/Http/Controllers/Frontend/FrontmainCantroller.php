<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
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
use Imageresize;
use DB;
use Auth;
use URL;
use File;
use Mail;
use Redirect;

class FrontmainCantroller extends Controller
{   
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
    public function create()
    {
        return view('front.register');
    }
    public function store(Request $request)
    {
        $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:users',
        'mobile' => 'required|numeric',    
        'password' => 'required|min:6',
        'confirm_password' => 'required|min:6|max:20|same:password',
         ]);
          $input = request()->all();
          $remember_token     = \Hash::make(uniqid());
          $users    = new User;
          $users->first_name    = $input['first_name'];
          $users->last_name     = $input['last_name'];
          $users->email         = $input['email'];
          $users->mobile        = $input['mobile'];
          $users->password      = Hash::make($input['password']);
          $users->remember_token  = $remember_token;
          $users->status = '1';
          $users->working_status ='0';
          if($users->save())
          {
              $role_id = Role::where('name', 'customer')->first();
              $add_userroles = new Userrole;
              $add_userroles->user_id = $users->id;
              $add_userroles->role_id = $role_id->id;
             if($add_userroles->save())
             {
                return back()->with('success', 'User created successfully.');
             }
             else
             {
                  return back()->with('error', 'Something Wrong with Registration');
             }   
          }
          else
          {
            return back()->with('error', 'Something Wrong with Registration');
          }      
    }


    public function home()
    {
        return view('front.front');
    }
    public function about_us()
    {
    	return view('front.about_us');
    }
    public function Services()
    {
    	return view('front.Services');
    }
    public function Pricing()
    {
        return view('front.pricing');
    }
    public function Contact()
    {
        return view('front.contact');
    }
    public function Login()
    {
        return view('front.login');
    }

    public function privacypolicy()
    {
        return view('front.Privacy');
    }
    public function termsconditions()
    {
        return view('front.terms&condtion');
    }
    public function blog()
    {
        return view('front.Blog');
    }
}
