<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TrainerMiddleware
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
        if(Auth::guard('front_auth')->check()){
            if (Auth::guard('front_auth')->user()->user_role == 'trainer')
            {
                return $next($request);
            }else{
                return redirect()->guest('/');
            }
        }
        
    }
}
