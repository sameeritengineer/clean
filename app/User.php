<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'mobile'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    public function roles()
    {
        //return $this->belongsToMany('App\Role')->withTimestamps();
        return $this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id')->withTimestamps();
    }

    public function user_address()
    {
        return $this->belongsTo('App\Useraddress','id','userId');
    }
    
    public function provider_locations()
    {
        return $this->hasMany('App\ProviderLocation');
    }
}
