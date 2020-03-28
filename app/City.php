<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $hidden = ['created_at','updated_at'];
	//protected $fillable = [ 'name', 'state_id', ];
    public function state()
    {
    	return $this->belongsTo('App\State');
    }

    public function zipcode()
    {
    	return $this->hasMany('App\Zipcode');
    }

    public function user_address()
    {
    	return $this->belongsTo('App\Useraddress');
    }
}
