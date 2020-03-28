<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Response;

class checkUser
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
           $user = Auth::User()->id;
           $userRole = Auth::User()->role;

          if ($userRole != 'Admin')
        {

            return new Response(view('unauthorized'))

        }
            else
        {

            return $next($request);
        }
       
    }
}
