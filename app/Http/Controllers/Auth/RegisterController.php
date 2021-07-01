<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Student;

use Carbon\Carbon;

use Log;
use Hash;
use Str;
use Redirect;

class RegisterController extends Controller
{
    public function index_register()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
            'c_password' => 'required',
        ]);

        if($request->password != $request->c_password){
            return Redirect::back()
                ->withErrors(['message' => 'Register gagal!, konfirmasi password salah.'])
                ->withInput();
        }

        $email_check = DB::table('students')
            ->where('email', $request->email)
            ->first();

        if($email_check){
            return Redirect::back()
                ->withErrors(['message' => 'Register gagal!, email tidak tersedia.'])
                ->withInput();
        }

        $username_check = DB::table('students')
            ->where('username', $request->username)
            ->first();

        $username_admin_check = DB::table('admins')
            ->where('username', $request->username)
            ->first();

        $username_coach_check = DB::table('coaches')
            ->where('username', $request->username)
            ->first();

        if($username_check || $username_admin_check || $username_coach_check || !preg_match('/^[a-zA-Z0-9._]+$/', $request->username)){
            return Redirect::back()
                ->withErrors(['message' => 'Register gagal!, username tidak tersedia.'])
                ->withInput();
        }

        $result = DB::transaction(function () use($request){

            activity()->withProperties([
                'method'=> request()->method(),
                'ip'    => request()->ip(),
                'username' => $request->username,
                'password' => $request->password,
                'email' => $request->email,
                'status' => 'register'
            ])
            ->log(request()->path());

            $token_verification = hash_hmac('sha256', \Illuminate\Support\Str::random(40), config('key.app_key'));

            $account = Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'token_verification' => $token_verification,
                'token_expired_at' => date('Y-m-d H:i:s', strtotime('+1 days'))
            ]);

            Mail::send('mail.email-verification', compact('account'), function($message) use($request){
                $message->to($request->email, $request->name)
                    ->from('info@mejaseni.com', 'MEJASENI')
                    ->subject('Email Verification');
            });
        });

        return redirect('register-success');
    }

    public function email_verification($token)
    {
        try {
            $student = Student::where('token_verification', $token)->first();

            if(!$student){
                abort(404, 'Token Not Found');
            }

            if($student->token_expired_at <= date('Y-m-d H:i:s')){
                abort(419, 'Token Expired');
            }

            $student->token_verification = null;
            $student->token_expired_at = null;
            $student->active = true;
            $student->verified = true;
            $student->update();

            Auth::guard('student')->login($student);

            return redirect('/');
        } catch (Exception $e) {
            throw new Exception($e);
            abort(500);
        }
    }

    public function registration_success()
    {
        return view('auth.register-success');
    }

    public function reset_password_success()
    {
        return view('auth.forgot-password-success');
    }

    public function resend_verification()
    {
        return view('user.redirect.resend-verification');
    }

    public function post_resend_verification(Request $request)
    {
        try {
            $token_verification = hash_hmac('sha256', \Illuminate\Support\Str::random(40), config('app.key'));

            $student = Student::where('email', $request->email)->first();

            if(!$student){
                return Redirect::back()->with('error','Email tidak ditemukan.')->withInput();
            }

            if($student->active && $student->verified){
                return Redirect::back()->with('error','Akun telah aktif.')->withInput();
            }

            $student->update([
                'token_verification' => $token_verification,
                'token_expired_at' => date('Y-m-d H:i:s', strtotime('+1 days'))
            ]);

            $student = Student::where('id', $student->id)->first();

            Mail::send('mail.email-verification', compact('account'), function($message) use($student){
                $message->to($student->email, $student->name)
                    ->from('info@mejaseni.com', 'MEJASENI')
                    ->subject('Email Verification');
            });

            return redirect('email/verification-success');
        } catch (Exception $e) {
            throw new Exception($e);
            return $e->getMessage();
        }
    }

    public function index_forgot_password()
    {
        return view('auth.forgot-password');
    }

    public function forgot_password(Request $request)
    {
        try {
            $digits = 8;
            $new_password = rand(pow(10, $digits-1), pow(10, $digits)-1);

            $student = Student::where('email', $request->email)->first();
            if(!$student){
                return Redirect::back()->with('error','Email tidak ditemukan.')->withInput();
            }

            $result = DB::transaction(function () use($new_password, $student){
                $student->password = Hash::make($new_password);
                $student->update();

                $student = Student::find($student->id);

                Mail::send('mail.forgot-password', compact('student', 'new_password'), function($message) use($student){
                    $message->to($student->email, $student->name)
                        ->from('info@mejaseni.com', 'MEJASENI')
                        ->subject('Email Verification');
                });

                return $student;
            });

            return redirect('email/forgot-password-success');
        } catch (Exception $e) {
            throw new Exception($e);
            return $e->getMessage();
        }
    }

    public function forgot_password_success()
    {
        return view('user.redirect.forgot-password-success');
    }
}
