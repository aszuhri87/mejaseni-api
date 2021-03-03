<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function notification()
    {
        try {
            if (Auth::guard('student')->check()) {
                $result = DB::table('student_notifications')
                    ->where('student_id', Auth::guard('student')->user()->id)
                    ->whereNull('deleted_at')
                    ->whereDate('datetime', date('Y-m-d'))
                    ->orderBy('datetime','desc')
                    ->get();

            }else if(Auth::guard('coach')->check()){
                $result = DB::table('coach_notifications')
                    ->where('coach_id', Auth::guard('coach')->user()->id)
                    ->whereNull('deleted_at')
                    ->whereDate('datetime', date('Y-m-d'))
                    ->orderBy('datetime','desc')
                    ->get();

            }else{
                $student = DB::table('student_notifications')
                    ->whereNull('deleted_at')
                    ->whereDate('datetime', date('Y-m-d'))
                    ->orderBy('datetime','desc')
                    ->get()
                    ->toArray();

                $coach = DB::table('coach_notifications')
                    ->whereNull('deleted_at')
                    ->whereDate('datetime', date('Y-m-d'))
                    ->orderBy('datetime','desc')
                    ->get()
                    ->toArray();

                $arr = array_merge($student, $coach);

                usort($arr, function($a, $b) {
                    return strtotime($a->datetime) <=> strtotime($b->datetime);
                });

                $result = array_reverse($arr, false);
            }

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}
