<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    public function service()
    {
    	return $this->belongsTo('App\Service');
    }

    public function coupon_applieds()
    {
    	return $this->hasMany('App\CouponApplied');
    }
}
