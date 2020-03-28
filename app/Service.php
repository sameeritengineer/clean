<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table="services";

    public function servicetypes()
    {
    	return $this->hasMany('App\Servicetype');
    }

    public function promo()
    {
    	return $this->hasOne('App\Promo');
    } 
}
