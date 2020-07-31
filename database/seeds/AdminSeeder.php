<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = \App\Role::count();
        if($roles == 0)
        {
            \App\Role::create(['role_name'=>'admin']);
            \App\Role::create(['role_name'=>'provider']);
            \App\Role::create(['role_name'=>'customer']);
            \App\Role::create(['role_name'=>'manager']);
            \App\Role::create(['role_name'=>'supervisior']);
            \App\Role::create(['role_name'=>'support']);
        }
        $admin = User::whereHas( 'roles', function($q){ $q->where('role_name', 'admin'); } )->first();
        if($admin == null)
        {
            $user = new User;
            $user->first_name = 'Cleaner';
            $user->email = 'info@cleaner.com';
            $user->mobile = '9999999999';
            $user->password = \Hash::make('cleaner@123');
            $user->status = 1;
            $user->working_status = 1;
            if($user->save())
            {
                $role = Role::where('role_name','admin')->first();
                $user->roles()->attach($role);  
            } 
        }
        $working_days = \App\Model\WorkingDay::count();
        if($working_days == 0)
        {
            \App\Model\WorkingDay::create(['day'=>'Sunday','status'=>'1']);
            \App\Model\WorkingDay::create(['day'=>'Monday','status'=>'1']);
            \App\Model\WorkingDay::create(['day'=>'Tuesday','status'=>'1']);
            \App\Model\WorkingDay::create(['day'=>'Wednesday','status'=>'1']);
            \App\Model\WorkingDay::create(['day'=>'Thursday','status'=>'1']);
            \App\Model\WorkingDay::create(['day'=>'Friday','status'=>'1']);
            \App\Model\WorkingDay::create(['day'=>'Saturday','status'=>'1']);
        }
    }
}
