<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Block;
use App\AdminSession;
class NotificationController extends Controller
{
    public function prevent_notification()
    {
    	$blocks = Block::get()->pluck('user_id');
    	$users = User::join('user_roles','users.id','=','user_roles.user_id')
    	->join('roles','roles.id','=','user_roles.role_id')
    	->whereHas('roles', function ($query){ $query->where('name','provider'); })
    	->whereNotIn('users.id',$blocks)
    	->select('users.*','roles.name as role_name')->distinct('users.id')->get();

    	$blocked = User::join('blocks','users.id','=','blocks.user_id')->where('blocks.time','!=',Null)->get();

    	return view('admin.notifications.prevent_notification',compact('users','blocked'));
    }


    public function admin_session(Request $request)
    {
        return $request->all();
    }
}
