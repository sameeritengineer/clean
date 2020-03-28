<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminComment extends Model
{
    protected $table="admin_comments";
    protected $fillable=['user_id','comments','job_id'];
}
