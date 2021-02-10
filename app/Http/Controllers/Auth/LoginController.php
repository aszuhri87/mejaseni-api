<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Admin;

use Carbon\Carbon;
use Log;

use Redirect;

class LoginController extends Controller
{
    public function index_login()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if(!isset($request->username) || !isset($request->password)){
            return Redirect::back()->withErrors(['message' => 'Login failed!, check your username or password.']);
        }

        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $login = [
            $loginType => $request->username,
            'password' => $request->password
        ];

        if (Auth::guard('admin')->attempt($login)) {
            activity()->withProperties([
                'method'=> request()->method(),
                'ip'    => request()->ip(),
                'username' => $request->username,
                'password' => $request->password,
                'guard' => 'admin',
                'status' => 'success'
            ])
            ->log(request()->path());

            return redirect('/admin/dashboard');
        }else if (Auth::guard('coach')->attempt($login)) {
            activity()->withProperties([
                'method'=> request()->method(),
                'ip'    => request()->ip(),
                'username' => $request->username,
                'password' => $request->password,
                'guard' => 'coach',
                'status' => 'success'
            ])
            ->log(request()->path());

            return redirect('/coach/dashboard');
        }else if (Auth::guard('student')->attempt($login)) {
            activity()->withProperties([
                'method'=> request()->method(),
                'ip'    => request()->ip(),
                'username' => $request->username,
                'password' => $request->password,
                'guard' => 'student',
                'status' => 'success'
            ])
            ->log(request()->path());

            return redirect('/student/dashboard');
        }else{
            activity()->withProperties([
                'method'=> request()->method(),
                'ip'    => request()->ip(),
                'username' => $request->username,
                'password' => $request->password,
                'status' => 'failed'
            ])
            ->log(request()->path());

            return Redirect::back()->withErrors(['message' => 'Login failed!, check your username or password.']);
        }
    }

    public function logout()
    {
        if(Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }else if(Auth::guard('coach')->check()) {
            Auth::guard('coach')->logout();
        }else if(Auth::guard('student')->check()) {
            Auth::guard('student')->logout();
        }

        return redirect('/login');
    }
}
