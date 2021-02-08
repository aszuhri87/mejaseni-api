<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CoachHandling
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(\Auth::guard('coach')->check()){
            return $next($request);
        }else{
            abort(403);
        }
    }
}
