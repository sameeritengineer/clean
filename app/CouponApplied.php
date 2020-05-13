<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponApplied extends Model
{
    public function promo()
    {
    	return $this->belongsTo('App\Promo');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    } 
}
