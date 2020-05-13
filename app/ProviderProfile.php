<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    public function provider()
    {
    	return $this->belongsTo('App\User','serviceprovider_id','id');
    }
}