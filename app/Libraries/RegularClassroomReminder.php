<?php

namespace App\Libraries;

use DB;
use Auth;

class RegularClassroomReminder
{
    public static function classroom_check()
    {
        date_default_timezone_set("Asia/Jakarta");

        $now = date('Y-m-d H:i:s');

        $id = Auth::guard('student')->user()->id;

        $coach_schedule = DB::table('coach_schedules')
            ->select([
                'coach_schedules.id',
                'coach_schedules.datetime',
            ])
            ->whereNull('coach_schedules.deleted_at')
            ->where('coach_schedules.accepted', true)
            ->groupBy('coach_schedules.id');

        $student_schedule = DB::table('student_schedules')
            ->select([
                'student_schedules.student_classroom_id',
            ])
            ->rightJoinSub($coach_schedule, 'coach_schedules', function ($join) {
                $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
            })
            ->whereNull('student_schedules.deleted_at')
            ->whereNotNull('student_schedules.student_classroom_id');

        $sub_student_classroom = DB::table('student_classrooms')
            ->select([
                'student_classrooms.id',
                DB::raw("COUNT(student_schedules.student_classroom_id) as total_scheduled")
            ])
            ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
            })
            ->where('student_classrooms.student_id', $id)
            ->whereNull('student_classrooms.deleted_at')
            ->groupBy('student_classrooms.id');

        $classroom = DB::table('classrooms')
            ->select([
                'classrooms.id',
                'classrooms.name',
                'classrooms.session_total',
            ])
            ->where('classrooms.package_type', 2)
            ->whereNull('classrooms.deleted_at');

        $transaction_classes = DB::table('transaction_details')
            ->select([
                'classrooms.id',
                'transactions.datetime'
            ])
            ->leftJoin('transactions', 'transactions.id','transaction_details.transaction_id')
            ->leftJoin('carts','carts.id','transaction_details.cart_id')
            ->join('classrooms','classrooms.id','carts.classroom_id')
            ->where('transactions.student_id', $id)
            ->where('transactions.status', 2)
            ->whereNull(['transactions.deleted_at']);

        $result = DB::table('student_classrooms')
            ->select([
                'classrooms.id as classroom_id',
                'classrooms.name as classroom_name',

                'sub_student_classroom.total_scheduled',
                'classrooms.session_total',
                DB::raw("(classrooms.session_total::integer / 2)::integer as mid_session_total"),

                DB::raw("CASE
                    WHEN (transaction_classes.datetime::timestamp + INTERVAL '1 MONTH')::timestamp > '{$now}'::timestamp
                        AND sub_student_classroom.total_scheduled < (classrooms.session_total::integer / 2)::integer
                        THEN ((classrooms.session_total::integer / 2)::integer) - sub_student_classroom.total_scheduled::integer

                    WHEN (transaction_classes.datetime::timestamp + INTERVAL '2 MONTH')::timestamp > '{$now}'::timestamp
                        AND sub_student_classroom.total_scheduled < classrooms.session_total
                        THEN (classrooms.session_total::integer - sub_student_classroom.total_scheduled::integer) - (classrooms.session_total::integer / 2)::integer

                    ELSE 0
                END subtraction"),

                DB::raw("CASE
                    WHEN (transaction_classes.datetime::timestamp + INTERVAL '1 MONTH')::timestamp > '{$now}'::timestamp
                        THEN (transaction_classes.datetime::timestamp + INTERVAL '1 MONTH')::timestamp
                    ELSE transaction_classes.datetime::timestamp + INTERVAL '2 MONTH'
                END expired_date"),

                DB::raw("CASE
                    WHEN ((transaction_classes.datetime::timestamp + INTERVAL '1 MONTH')::timestamp) > '{$now}'::timestamp
                        AND sub_student_classroom.total_scheduled < (classrooms.session_total::integer / 2)::integer
                        THEN true
                    WHEN (transaction_classes.datetime::timestamp + INTERVAL '2 MONTH') > '{$now}'::timestamp
                        AND sub_student_classroom.total_scheduled < (classrooms.session_total - (classrooms.session_total::integer / 2)::integer)
                        THEN true
                    ELSE false
                END reminder_status"),

                DB::raw("CASE
                    WHEN ((transaction_classes.datetime::timestamp + INTERVAL '1 MONTH')::timestamp) > '{$now}'::timestamp
                        AND sub_student_classroom.total_scheduled < (classrooms.session_total::integer / 2)::integer
                        THEN 1
                    ELSE 2
                END reminder_period"),
            ])
            ->joinSub($classroom, 'classrooms', function ($join) {
                $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
            })
            ->leftJoinSub($sub_student_classroom, 'sub_student_classroom', function ($join) {
                $join->on('student_classrooms.id', '=', 'sub_student_classroom.id');
            })
            ->leftJoinSub($transaction_classes, 'transaction_classes', function ($join) {
                $join->on('classrooms.id', '=', 'transaction_classes.id');
            })
            ->where('student_classrooms.student_id', $id)
            ->whereNull('student_classrooms.deleted_at')
            ->whereNotNull('classrooms.id')
            ->get();

        return $result;
    }
}
