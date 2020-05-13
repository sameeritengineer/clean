<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderReview extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\User','id','provider_id');
    }
}
