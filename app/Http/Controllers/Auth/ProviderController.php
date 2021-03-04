<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Student;

class ProviderController extends Controller
{
    public function redirect_provider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback_provider($provider)
    {
        $data = Socialite::driver($provider)->stateless()->user();

        if($data->email == null ){
            return redirect('/')->with('error', 'Email Harus Dipublik');
        }

        $user = Student::where('email', $data->email)->first();

        if($user){

            Auth::guard('student')->login($user);

        }else{
            $user = DB::transaction(function () use($data,$provider){
                $user = Student::create([
                    'name' => $data->name,
                    'email' => $data->email,
                    'image' => $data->avatar,
                    'active' => true,
                    'verified' => true,
                    'provider' => true,
                ]);

                return $user;
            });

            Auth::guard('student')->login($user);
        }

        return redirect('/student/dashboard');
    }
}
