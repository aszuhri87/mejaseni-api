<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ScheduleRequest;
use App\Models\StudentNotification;
use App\Models\Classroom;
use App\Models\StudentSchedule;

use App\Libraries\SocketIO;

use DB;
use Auth;
use DataTables;
use DateTime;

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

                $startDate = $this->start_date($request->classroom_id);

                while ($i < $session){

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

    public function single_request(Request $request)
    {
        try {
            $schedule_check = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'schedule_requests.datetime'
                ])
                ->leftJoin('coach_classrooms', 'coach_classrooms.id','=', 'coach_schedules.coach_classroom_id')
                ->leftJoin('student_schedules', 'student_schedules.coach_schedule_id','=', 'coach_schedules.id')
                ->leftJoin('schedule_requests', 'schedule_requests.id','=', 'coach_schedules.schedule_request_id')
                ->leftJoin('sessions', 'student_schedules.session_id','=', 'sessions.id')
                ->where('coach_schedules.id', $request->coach_schedule_id)
                ->first();

            if($schedule_check){
                $date = date('Y-m-d', strtotime($schedule_check->datetime));

                $end_date = date('Y-m-d', strtotime($date. ' + 7 days'));

                if(date('Y-m-d', strtotime($request->date)) > $end_date){
                    return response([
                        "status"    => 400,
                        "message"   => 'Reschedule maksimal 7 hari dari tanggal sebelumnya'
                    ], 400);
                }
            }

            $result = DB::transaction(function () use($request){
                $coach_schedule = DB::table('coach_schedules')
                    ->select([
                        'coach_schedules.id',
                        'coach_classrooms.classroom_id',
                        'sessions.name as session',
                        'student_schedules.student_classroom_id',
                    ])
                    ->leftJoin('coach_classrooms', 'coach_classrooms.id','=', 'coach_schedules.coach_classroom_id')
                    ->leftJoin('student_schedules', 'student_schedules.coach_schedule_id','=', 'coach_schedules.id')
                    ->leftJoin('sessions', 'student_schedules.session_id','=', 'sessions.id')
                    ->where('coach_schedules.id', $request->coach_schedule_id)
                    ->first();

                StudentSchedule::where([
                    'student_classroom_id' => $coach_schedule->student_classroom_id,
                    'coach_schedule_id' => $coach_schedule->id,
                ])->delete();

                $request_schedule = ScheduleRequest::create([
                    'classroom_id' => $coach_schedule->classroom_id,
                    'student_id' => Auth::guard('student')->user()->id,
                    'datetime' => date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time)),
                    'session' => $coach_schedule->session,
                    'reschedule' => true
                ]);

                return $request_schedule;
            });

            if($result){
                $classroom = Classroom::find($result->classroom_id);

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

            $request_count = DB::table('schedule_requests')
                ->whereNull('schedule_requests.deleted_at')
                ->where('schedule_requests.student_id', Auth::guard('student')->user()->id)
                ->where('classroom_id', $id)
                ->where(function($query){
                    $query->where('coach_confirmed', true)
                        ->orWhereNull('coach_confirmed');
                })
                ->count();

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.session_total',
                ])
                ->where('id', $id)
                ->first();

            return $classroom ? $classroom->session_total - $request_count : 0;
        } catch (Exception $e) {
            throw new Exception($e);
            return false;
        }
    }

    public function start_date($classroom_id)
    {
        $data = DB::table('schedule_requests')
            ->whereNull('schedule_requests.deleted_at')
            ->where('schedule_requests.student_id', Auth::guard('student')->user()->id)
            ->where('classroom_id', $classroom_id)
            ->orderBy('datetime','desc')
            ->whereNull('schedule_requests.deleted_at')
            ->first();

        if($data){
            $date = new DateTime($data->datetime);
            $date->modify('next monday');
            return $date->format('Y-m-d');
        }else{
            return date('Y-m-d', strtotime('next monday'));
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
                        WHEN coaches.id IS NULL THEN 'Menunggu Konfirmasi Admin'
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'Menunggu Konfirmasi Coach'
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

    public function request_list()
    {
        try {
            $data = DB::table('schedule_requests')
                ->select([
                    'schedule_requests.id',
                    'schedule_requests.datetime',
                    'classrooms.name',
                    DB::raw("CASE
                        WHEN coaches.id IS NULL THEN 'Menunggu Konfirmasi Admin'
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'Menunggu Konfirmasi Coach'
                        WHEN schedule_requests.coach_confirmed = true THEN 'Dikonfirmasi Coach'
                        ELSE 'Ditolak Coach'
                    END status"),
                    DB::raw("CASE
                        WHEN coaches.id IS NULL THEN 'warning'
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'success'
                        WHEN schedule_requests.coach_confirmed = true THEN 'primary'
                        ELSE 'dark'
                    END status_color"),
                    DB::raw("3 as type")
                ])
                ->leftJoin('classrooms','classrooms.id','=','schedule_requests.classroom_id')
                ->leftJoin('students','students.id','=','schedule_requests.student_id')
                ->leftJoin('coaches','coaches.id','=','schedule_requests.coach_id')
                ->where(function($query){
                    $query->where('schedule_requests.coach_confirmed',false)
                        ->orWhereNull('schedule_requests.coach_confirmed');
                })
                ->whereNull('schedule_requests.deleted_at')
                ->where('schedule_requests.student_id', Auth::guard('student')->user()->id)
                ->get();

            return response([
                "data"      => $data,
                "message"   => 'OK!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        try {
            $result = DB::table('schedule_requests')
                ->select([
                    'schedule_requests.*',
                    'classrooms.name as classroom',
                    'students.name as student',
                    'coaches.name as coach',
                    DB::raw("CASE
                        WHEN coaches.id IS NULL THEN 'Menunggu Konfirmasi Admin'
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'Menunggu Konfirmasi Coach'
                        WHEN schedule_requests.coach_confirmed = true THEN 'Dikonfirmasi Coach'
                        ELSE 'Ditolak Coach'
                    END status"),
                    DB::raw("CASE
                        WHEN coaches.id IS NULL THEN 'warning'
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'success'
                        WHEN schedule_requests.coach_confirmed = true THEN 'primary'
                        ELSE 'dark'
                    END status_color"),
                ])
                ->leftJoin('classrooms','classrooms.id','=','schedule_requests.classroom_id')
                ->leftJoin('students','students.id','=','schedule_requests.student_id')
                ->leftJoin('coaches','coaches.id','=','schedule_requests.coach_id')
                ->where('schedule_requests.id', $id)
                ->first();

            return response([
                'data' => $result,
                "message"   => 'OK!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
}
