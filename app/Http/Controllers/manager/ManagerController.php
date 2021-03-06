<?php

namespace App\Http\Controllers\manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;
use App\Userrole;
use Auth;
class ManagerController extends Controller
{
    public function register(Request $request)
    {
    	if($request->isMethod('get'))
    	{
    		return view('manager.register');
    	}
    	if($request->isMethod('post'))
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
	        $users->status = 1;
	        $users->working_status = 0;
	        if($users->save())
	        {
	            $roles = Role::where('name','manager')->first();
	            if($roles != null)
	            {
	                $userroles = new Userrole;
	                $userroles->user_id = $users->id;
	                $userroles->role_id = $roles->id;
	                if($userroles->save())
	                {
	                    return redirect()->route('manager::login');
	                }
	                else
	                {
	                    return view('manager.register');
	                }
	            }
	            else
	            {
	                return view('manager.register');
	            }
	        }
	        else
	        {
	            return view('manager.register');
	        }
	    }
    }

    public function login(Request $request)
    {
    	if($request->isMethod('get'))
    	{
    		return view('manager.login');
    	}
    	if($request->isMethod('post'))
    	{
    		$this->validate($request, [
	            'email'     => 'required|string|email|max:255',
	            'password'  => 'required|string|min:6',
	        ]);
	        $user = User::whereEmail($request->email)->first();
	        if($user != null)
	        {
	        	if (Hash::check($request->password, $user->password))
	        	{
	        		Auth::login($user);
	        		return redirect()->route('manager::dashboard');
				}
				else
				{
					return redirect()->route('manager::login');
				}
	        }
	        else
	        {
	        	return redirect()->route('manager::login');
	        }
    	}
    }

    public function logout()
    {
    	Auth::logout();
        return redirect()->route('manager::login');
    }

    
}
