<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zipcode extends Model
{
	protected $hidden = ['created_at','updated_at'];
	protected $fillable = [ 'zipcode', 'city_id', ];
    public function city()
    {
    	return $this->belongsTo('App\City');
    }

    public function user_address()
    {
    	return $this->belongsTo('App\Useraddress');
    }
}
