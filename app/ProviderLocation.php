<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderLocation extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\User','provider_id','id');
    }
}
