<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use setCookie;
//use App\Traits\CacheQueryResults;

class SessionTimeOut
{
    //use CacheQueryResults;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // session()->forget('lastActivityTime');

        if (! session()->has('lastActivityTime')) {
            session(['lastActivityTime' => now()]);
        }
//var_dump($request->route());
        //$request = $request->all();
        /* dd(
             session('lastActivityTime')->format('Y-M-jS h:i:s A'),
             now()->diffInMinutes(session('lastActivityTime')),
             now()->diffInMinutes(session('lastActivityTime')) >= config('session.lifetime')
         );*/


    //dd(now()->diffInMinutes(session('lastActivityTime')));
         if (now()->diffInMinutes(session('lastActivityTime')) >= (118) ) {  // also you can this value in your config file and use here
            
               if(Auth::guard('front_auth')->check() == true){
                //dd(Auth::guard('front_auth')->user()->id);
                   //$user = auth()->user();
                   //$user->update(['phone_number' => $request['phone_number']]);
                   //dd($request);exit();
                    //echo "<script>alert('session expiryed');</script>";
                   Auth::guard('front_auth')->logout();

                   //$user->update(['is_logged_in' => false]);
                   //$this->reCacheAllUsersData();

                   session()->forget('lastActivityTime');
                   //session()->flash('error', 'Session expired');
                   return response()->view('front.auth.timeoutalert');exit();
                   //return redirect()->route('front.logout');
                   //return redirect('/logout');
               }
          

       } //return response()->view('front.auth.timeoutalert');exit();

       session(['lastActivityTime' => now()]);

       return $next($request);
    }
}
