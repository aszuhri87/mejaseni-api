<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentHandling
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
        if(\Auth::guard('student')->check()){
            return $next($request);
        }else{
            abort(403);
        }
    }
}
