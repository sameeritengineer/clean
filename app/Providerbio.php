<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Providerbio extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function provider()
    {
    	return $this->belongsTo('App\User','serviceprovider_id','id');
    }
}
