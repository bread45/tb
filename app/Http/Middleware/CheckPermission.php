<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $route_name = \Route::currentRouteName();
        if (isAuthorize($route_name)) {
            return $next($request);
        }
//        return response()->view('errors.permission_denied');
        return abort(404);
    }

}
