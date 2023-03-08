<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\Users\Entities\FrontUsers;

class UserMiddleware
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
            session()->put('booknowpage', false);
            return $next($request);
        }else{
            //dd("book", $request->has('booknow'));
            if ($request->has('booknow')){
                $tabName = "customer";
                session()->put('tabName', "customer");
                session()->put(['booknowpage' => true, 'link' => url()->previous()]);
                $previousUrl = url()->previous();
                $segments = explode('/', $previousUrl);
                $segment = end($segments);

                $trainer = FrontUsers::where('spot_description', $segment)->first();
                
                //dd(route('customer.booknow',base64_encode($trainer->id)));
                session()->put('url.intended', route('customer.booknow',base64_encode($trainer->id)));
                return redirect()->route('front.login')->withErrors(['Please Login to book service!'])->with( ['booknow' => true] );
               
            }else{
                session()->put('url.intended', '/');
                session()->put('booknowpage', false);
                //return redirect()->guest('/');
                return redirect()->route('front.login')->with( ['booknow' => false] );
            }
        }
       
    }
}
