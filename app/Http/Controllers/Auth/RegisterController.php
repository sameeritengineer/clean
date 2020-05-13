<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use App\Userrole;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'mobile' => 'required|integer',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // return User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);
        $users = new User;
        $users->first_name = $data['first_name'];
        $users->last_name = $data['last_name'];
        $users->email = $data['email'];
        $users->password = Hash::make($data['password']);
        $users->status = 1;
        $users->working_status = 0;
        $users->mobile = $data['mobile'];
        if($users->save())
        {
            $roles =Role::where('name','customer')->first();
            //$roles->name = "customer";
            if($roles != null)
            {
                $userroles = new Userrole;
                $userroles->user_id = $users->id;
                $userroles->role_id = $roles->id;
                if($userroles->save())
                {
                    return $users;
                }
                else
                {
                    return "error";
                }
            }
            else
            {
                return "error";
            }
        }
        else
        {
            return "error";
        }
    }
}
