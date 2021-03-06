<?php

namespace App\Http\Middleware;

use Closure;
use App\Userrole;
use App\Role;
use Illuminate\Http\Response;
class Support
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Auth::check())
        {
            $userId = \Auth::id();              
            $userRole = Userrole::where('user_id',$userId)->first();
            $role = Role::find($userRole->role_id);
            if($role->name != "support")
            {
                return new Response(view('support.login'));
            }            
        }
        else
        {
            return new Response(view('support.login')); 
        }
        return $next($request);
    }
}
