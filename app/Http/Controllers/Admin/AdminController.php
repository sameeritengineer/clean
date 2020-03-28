<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Redirect;
use App\User;
use App\Role;
use App\Userrole;
use Auth;
class AdminController extends Controller
{
	public function register()
	{
		return view('admin.register');
	}

	public function postRegister(Request $request)
	{
		$this->validate($request, [
            'first_name'=> 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'mobile'    => 'required',
        ]);

		$users = new User;
        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->email = $request->email;
        $users->password = Hash::make($request->password);
        $users->mobile = $request->mobile;
        if($users->save())
        {
            $roles = new Role;
            $roles->name = "admin";
            if($roles->save())
            {
                $userroles = new Userrole;
                $userroles->user_id = $users->id;
                $userroles->role_id = $roles->id;
                if($userroles->save())
                {
                    return redirect('/admin/login');
                }
                else
                {
                    return view('admin.register');
                }
            }
            else
            {
                return view('admin.register');
            }
        }
        else
        {
            return view('admin.register');
        }
	}

    public function login(Request $request)
	{	
		$request->method();
		if ($request->isMethod('get'))
		{
			return view('admin.login');
		}
		if ($request->isMethod('post'))
		{
			return $request;
			// $user = User::where('email',$request->name)->first();
	  //       if(count($user)>0)
	  //       {
	  //           $checkRoles = Userrole::where('user_id',$user->id)->first();
	  //           if(count($checkRoles)>0)
	  //           {
	  //               $role = Role::find($checkRoles->role_id);                
	  //               $data = $request->only('email','password');
	  //               $collection = collect($data);
	  //               $collection->put('role', $role->name);
	  //               $collection->all();
	  //               $credentials = ($collection);
	  //               if(Auth::login($credentials, true))
	  //               {
	  //                    // Authentication passed...
	  //                   return "login";
	  //                   // return redirect()->intended('admin/dashboard');
	  //               }
	  //           }
	  //       }
		}		
	}
	public function recoverPassword(Request $request)
	{		
		$request->method();
		if ($request->isMethod('get'))
		{
			return view('admin.recover-password');
		}
		if ($request->isMethod('post'))
		{
			return $request;
		}
	}
}
