<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Userrole;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/serviceadmin';

    
        //   public function authenticated()
        // {

        //      $role = Auth::user()->role;

        //     if($role == 'Admin')
        //     {
        //      return redirect('/serviceadmin');

        //     }

        //     else
        //     {

        //         return redirect('admin.login');
        //     } 

        // }





    // public function login(Request $request)
    // {
    //     //return $request->all();
    //     $user = User::where('email',$request->email)->first();
    //     if(count($user)>0)
    //     {
    //         $checkRoles = Userrole::where('user_id',$user->id)->first();
    //         if(count($checkRoles)>0)
    //         {
    //             $role = Role::find($checkRoles->role_id);                
    //             $data = $request->only('email','password');
    //             $collection = collect($data);
    //             $collection->put('role', $role->name);
    //             $collection->all();
    //             $credentials = ($collection);
    //             if(Auth::login($credentials, true))
    //             {
    //                  // Authentication passed...
    //                 return "login";
    //                 // return redirect()->intended('admin/dashboard');
    //             }
    //         }
    //     }
    // }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
