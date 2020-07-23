<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::whereHas( 'roles', function($q){ $q->where('name', 'admin'); } )->first();
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
                $role = Role::where('name','admin')->first();
                $user->roles()->attach($role);  
            } 
        }
    }
}
