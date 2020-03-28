<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Useraddress extends Model
{
    public function user()
    {
    	return $this->hasOne('App\User','userId','id');
    }

    public function country_name()
    {
    	return $this->hasOne('App\Country','id','country');
    }

    public function state_name()
    {
    	return $this->hasOne('App\State','id','state');
    }

    public function city_name()
    {
    	return $this->hasOne('App\City','id','city');
    }

    public function zipcode_name()
    {
    	return $this->hasOne('App\Zipcode','id','zipCode');
    }
}
