<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sptostype extends Model
{
     protected $fillable = ['serviceprovider_id','service_id','servicetype_id'];

    public function serviceprovider()
    {
    	return $this->belongsTo('App\User');
    }
}
