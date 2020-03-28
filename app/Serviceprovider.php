<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serviceprovider extends Model
{
      public function sptostypes()
    {
    	return $this->hasMany('App\Sptostype');
    }
}
