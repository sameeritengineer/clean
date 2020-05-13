<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function states()
    {
    	return $this->hasMany('App\State');
    }

    public function user_address()
    {
    	return $this->belongsTo('App\Useraddress');
    }

    public function parent()
	{
	    return $this->belongsTo(Country::class);
	}
}
