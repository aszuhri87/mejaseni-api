<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ScheduleRequest;
use App\Models\StudentNotification;
use App\Models\Classroom;

use App\Libraries\SocketIO;

use DB;
use Auth;
use DataTables;

class RequestScheduleController extends Controller
{
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        try {
            $same_day = false;
            foreach(array_count_values($request->day) as $val => $c)
                if($c > 2) $same_day = true;

            if($same_day){
                return response([
                    "status"    => 400,
                    "message"   => 'Ada hari yang sama lebih dari 2.'
                ], 400);
            }

            $data = DB::table('schedule_requests')
                ->whereNull('schedule_requests.deleted_at')
                ->where('schedule_requests.student_id', Auth::guard('student')->user()->id)
                ->where('classroom_id', $request->classroom_id)
                ->whereNull('coach_confirmed')
                ->count();

            if($data > 0){
                return response([
                    "status"    => 400,
                    "message"   => 'Permintaan gagal, ada request yang belum di konfirmasi.'
                ], 400);
            }

            $result = DB::transaction(function () use($request){
                $message = false;

                $session = $this->get_subtraction($request->classroom_id); $i = 0; $loop = 0;
                while ($i < $session){
                    $startDate = date('Y-m-d', strtotime('next monday'));

                    $date = date('Y-m-d', strtotime("{$startDate} +{$loop} days"));

                    $day = date('l', strtotime($date));

                    for ($index=0; $index < count($request->day); $index++) {
                        if($request->day[$index] == $day){
                            ScheduleRequest::create([
                                'classroom_id' => $request->classroom_id,
                                'student_id' => Auth::guard('student')->user()->id,
                                'datetime' => date('Y-m-d H:i:s', strtotime($date.' '.$request->time[$index])),
                                'session' => $i + 1
                            ]);

                            $i++;
                            $message = true;
                        }
                    }

                    $loop++;
                };

                return $message;
            });

            if($result){
                $classroom = Classroom::find($request->classroom_id);

                $notification = StudentNotification::create([
                    'student_id' => Auth::guard('student')->user()->id,
                    'type' => 4,
                    'text' => Auth::guard('student')->user()->name.' mengajukan jadwal untuk kelas '.$classroom->name,
                    'datetime' => date('Y-m-d H:i:s')
                ]);

                event(new \App\Events\StudentNotification($notification, Auth::guard('student')->user()->id));
                event(new \App\Events\AdminNotification($notification));

                SocketIO::message(Auth::guard('student')->user()->id, 'notification_'.Auth::guard('student')->user()->id, $notification);
                SocketIO::message('admin', 'notification_admin', $notification);
            }

            return response([
                "status"    => 200,
                "message"   => 'Permintaan terkirim'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function classroom()
    {
        try {
            date_default_timezone_set("Asia/Jakarta");

            $now = date('Y-m-d H:i:s');

            $id = Auth::guard('student')->user()->id;

            // count sisa pertemuan
            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                ])
                ->whereNull('coach_schedules.deleted_at')
                ->where('coach_schedules.accepted',true)
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
                    DB::raw("COUNT(student_schedules.student_classroom_id) as last_meeting")

                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->where('student_classrooms.student_id', $id)
                ->whereNull('student_classrooms.deleted_at')
                ->groupBy('student_classrooms.id');

            // end

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classrooms.session_total',
                    'classrooms.package_type',
                ])
                ->where('package_type',2)
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
                    'classrooms.id',
                    'classrooms.name',
                    'sub_student_classroom.last_meeting',
                    DB::raw("
                        CASE
                            WHEN classrooms.package_type = 2
                                THEN
                                    CASE
                                        WHEN (transaction_classes.datetime::timestamp + INTERVAL '1 MONTH')::timestamp > '{$now}'::timestamp
                                            AND sub_student_classroom.last_meeting < (classrooms.session_total::integer / 2)::integer
                                            THEN ((classrooms.session_total::integer / 2)::integer) - sub_student_classroom.last_meeting::integer
                                        WHEN (transaction_classes.datetime::timestamp + INTERVAL '2 MONTH')::timestamp > '{$now}'::timestamp
                                            AND sub_student_classroom.last_meeting < classrooms.session_total
                                            THEN (classrooms.session_total::integer - sub_student_classroom.last_meeting::integer) - (classrooms.session_total::integer / 2)::integer
                                        ELSE 0
                                    END
                            ELSE
                                CASE
                                    WHEN (transaction_classes.datetime::timestamp + INTERVAL '1 WEEKS')::timestamp > '{$now}'::timestamp
                                        THEN classrooms.session_total::integer  - sub_student_classroom.last_meeting::integer
                                    ELSE 0
                                END
                        END subtraction
                    "),
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

            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'OK!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_subtraction($id)
    {
        try {
            date_default_timezone_set("Asia/Jakarta");

            $now = date('Y-m-d H:i:s');

            $student_id = Auth::guard('student')->user()->id;

            // count sisa pertemuan
            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                ])
                ->whereNull('coach_schedules.deleted_at')
                ->where('coach_schedules.accepted',true)
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
                    DB::raw("COUNT(student_schedules.student_classroom_id) as last_meeting")

                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->where('student_classrooms.student_id', $student_id)
                ->whereNull('student_classrooms.deleted_at')
                ->groupBy('student_classrooms.id');

            // end

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classrooms.session_total',
                    'classrooms.package_type',
                ])
                ->where('package_type',2)
                ->whereNull('classrooms.deleted_at');

            $transaction_classes = DB::table('transaction_details')
                ->select([
                    'classrooms.id',
                    'transactions.datetime'
                ])
                ->leftJoin('transactions', 'transactions.id','transaction_details.transaction_id')
                ->leftJoin('carts','carts.id','transaction_details.cart_id')
                ->join('classrooms','classrooms.id','carts.classroom_id')
                ->where('transactions.student_id', $student_id)
                ->where('transactions.status', 2)
                ->whereNull(['transactions.deleted_at']);

            $result = DB::table('student_classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'sub_student_classroom.last_meeting',
                    DB::raw("
                        CASE
                            WHEN classrooms.package_type = 2
                                THEN
                                    CASE
                                        WHEN (transaction_classes.datetime::timestamp + INTERVAL '1 MONTH')::timestamp > '{$now}'::timestamp
                                            AND sub_student_classroom.last_meeting < (classrooms.session_total::integer / 2)::integer
                                            THEN ((classrooms.session_total::integer / 2)::integer) - sub_student_classroom.last_meeting::integer
                                        WHEN (transaction_classes.datetime::timestamp + INTERVAL '2 MONTH')::timestamp > '{$now}'::timestamp
                                            AND sub_student_classroom.last_meeting < classrooms.session_total
                                            THEN (classrooms.session_total::integer - sub_student_classroom.last_meeting::integer) - (classrooms.session_total::integer / 2)::integer
                                        ELSE 0
                                    END
                            ELSE
                                CASE
                                    WHEN (transaction_classes.datetime::timestamp + INTERVAL '1 WEEKS')::timestamp > '{$now}'::timestamp
                                        THEN classrooms.session_total::integer  - sub_student_classroom.last_meeting::integer
                                    ELSE 0
                                END
                        END subtraction
                    "),
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
                ->where('student_classrooms.student_id', $student_id)
                ->whereNull('student_classrooms.deleted_at')
                ->where('classrooms.id', $id)
                ->first();

            return $result ? $result->subtraction : 0;
        } catch (Exception $e) {
            throw new Exception($e);
            return false;
        }
    }

    public function dt(Request $request)
    {
        try {
            $data = DB::table('schedule_requests')
                ->select([
                    'schedule_requests.*',
                    'classrooms.name as classroom',
                    DB::raw("CASE
                        WHEN coaches.id IS NULL THEN 'Menunggu Admin'
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'Menunggu Coach'
                        WHEN schedule_requests.coach_confirmed = true THEN 'Dikonfirmasi Coach'
                        ELSE 'Ditolak Coach'
                    END status"),
                    DB::raw("CASE
                        WHEN coaches.id IS NULL THEN 'danger'
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'warning'
                        WHEN schedule_requests.coach_confirmed = true THEN 'success'
                        ELSE 'dark'
                    END status_color")
                ])
                ->leftJoin('classrooms','classrooms.id','=','schedule_requests.classroom_id')
                ->leftJoin('students','students.id','=','schedule_requests.student_id')
                ->leftJoin('coaches','coaches.id','=','schedule_requests.coach_id')
                ->whereNull('schedule_requests.deleted_at')
                ->where('schedule_requests.student_id', Auth::guard('student')->user()->id)
                ->get();

            return DataTables::of($data)->addIndexColumn()->make(true);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
}
