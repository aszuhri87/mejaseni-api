<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminHandling
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
        if(\Auth::guard('admin')->check()){
            return $next($request);
        }else{
            abort(403);
        }
    }
}
