<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class AuthHandling
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
        if(Auth::guard('admin')->check() || Auth::guard('coach')->check() || Auth::guard('student')->check()){
            activity()->withProperties([
                'method'=> request()->method(),
                'ip'    => request()->ip(),
            ])
            ->log(request()->path());

            return $next($request);
        }else{
            return redirect('/login');
        }
    }
}
