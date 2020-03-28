<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicetype extends Model
{
    protected $table="service_types";

    protected $fillable = ['name','service_id','image'];

    public function service()
    {
    	return $this->belongsTo('App\Service');
    }

    public function serviceprice()
    {
    	return $this->belongsTo('App\ServicePrice');
    }
}
