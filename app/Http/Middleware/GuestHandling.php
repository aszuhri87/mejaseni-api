<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class GuestHandling
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
        if(Auth::guard('admin')->check()){
            return redirect('/admin/dashboard');
        }else if(Auth::guard('coach')->check()){
            return redirect('/coach/dashboard');
        }else if(Auth::guard('student')->check()){
            return redirect('/student/dashboard');
        }else{
            return $next($request);
        }
    }
}
