<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;

use Auth;

class DashboardController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Dashboard'
            ],
        ];

        return view('student.dashboard.index', [
            'title' => 'Dashboard',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }

    public function total_class()
    {
        try {
            $data = DB::table('student_classrooms')
                ->where('student_classrooms.student_id', Auth::guard('student')->user()->id)
                ->whereNull('student_classrooms.deleted_at')
                ->whereNotNull('student_classrooms.id')
                ->count();

            return response([
                "status"  => 200,
                "data"    => $data,
                "message" => 'OK!'
            ],200);
        }catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }

    }

    public function total_video()
    {
        try {
            $cart = DB::table('carts')
                ->select([
                    'carts.id'
                ])
                ->whereNotNull('carts.session_video_id');


            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.transaction_id',
                    'carts.id as cart_id',
                ])
                ->joinSub($cart, 'carts', function ($join) {
                    $join->on('transaction_details.cart_id', '=', 'carts.id');
                })
                ->whereNull('transaction_details.deleted_at');

            $data = DB::table('transactions')
                ->select([
                    'transaction_details.cart_id',
                    DB::raw('COUNT(transaction_details.cart_id) as total_video')
                ])
                ->rightJoinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('transactions.id', '=', 'transaction_details.transaction_id');
                })
                ->where([
                    'transactions.status' => 2,
                    'transactions.confirmed' => true,
                    'transactions.student_id' => Auth::guard('student')->user()->id,
                ])
                ->whereNull('transactions.deleted_at')
                ->whereNotNull('transaction_details.cart_id')
                ->count();

            return response([
                "status"  => 200,
                "data"    => $data,
                "message" => 'OK!'
            ],200);
        }catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }

    }

    public function total_booking()
    {
        try {
            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'coach_schedules.accepted'
                ])
                ->where('coach_schedules.accepted',true)
                ->whereNull('coach_schedules.id');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.student_classroom_id',
                    'coach_schedules.id as coach_schedule_id',
                    'coach_schedules.datetime',
                    'coach_schedules.accepted',
                ])
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereDate('coach_schedules.datetime','>',date('Y-m-d'))
                ->whereNull('student_schedules.deleted_at');

            $data = DB::table('student_classrooms')
                ->select([
                    'student_schedules.coach_schedule_id'
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->where('student_classrooms.student_id', Auth::guard('student')->user()->id)
                ->where('student_classrooms.deleted_at')
                ->whereNotNull('student_schedules.coach_schedule_id')
                ->count();

            return response([
                "status"  => 200,
                "data"    => $data,
                "message" => 'OK!'
            ],200);
        }catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }

    }

    public function history_booking()
    {
        try {
            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'coach_schedules.accepted'
                ])
                ->where('coach_schedules.accepted',true)
                ->whereNull('coach_schedules.id');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.student_classroom_id',
                    'coach_schedules.id as coach_schedule_id',
                    'coach_schedules.datetime',
                    'coach_schedules.accepted',
                ])
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereDate('coach_schedules.datetime','<',date('Y-m-d'))
                ->whereNull('student_schedules.deleted_at');

            $data = DB::table('student_classrooms')
                ->select([
                    'student_schedules.coach_schedule_id'
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->where('student_classrooms.student_id', Auth::guard('student')->user()->id)
                ->where('student_classrooms.deleted_at')
                ->whereNotNull('student_schedules.coach_schedule_id')
                ->count();

            return response([
                "status"  => 200,
                "data"    => $data,
                "message" => 'OK!'
            ],200);
        }catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }

    }
}
