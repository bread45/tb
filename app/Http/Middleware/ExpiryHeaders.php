<?php

namespace App\Http\Middleware;

use Closure;

class ExpiryHeaders
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
        // $minutes = config(key: 'app.get_url_expiry');
        $minutes = 15;
        $expiry = now()->addMinutes( 43800 )->toRfc7231String();

        $response = $next($request);
        $response->header('Expires', $expiry);
        return $next($request);
    }
}
