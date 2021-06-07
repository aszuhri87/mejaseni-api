<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Session;
use App\Models\StudentSchedule;
use App\Models\Cart;
use App\Models\Classroom;

use Storage;
use Auth;

class ScheduleController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Schedule'
            ],
        ];

        $path = Storage::disk('s3')->url('/');
        $data = DB::table('students')
            ->select([
                'students.*',
                DB::raw("CONCAT('{$path}',students.image) as image_url"),
            ])
            ->whereNull('students.deleted_at')
            ->where('id',Auth::guard('student')->user()->id)
            ->first();

        return view('student.schedule.index', [
            'title' => 'Schedule',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
            'data' => $data,
        ]);
    }

    public function regular_class()
    {
        try {
            /*
                status
                1 => Terlewat Tanggal
                2 => Booking
                3 => Tersedia
            */

            $date = date('Y-m-d H:i:s');
            $student_id = Auth::guard('student')->user()->id;

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classrooms.price',
                ])
                ->where('package_type',2)
                ->whereNull('deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.classroom_id',
                    'coach_classrooms.coach_id',
                    'classrooms.name as classroom_name',
                    'classrooms.price',
                ])
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->whereNull('coach_classrooms.deleted_at');

            $student_classroom = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    'student_classrooms.classroom_id',
                    'student_classrooms.student_id',
                ])
                ->whereNull('student_classrooms.deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.coach_schedule_id',
                    'student_schedules.student_classroom_id',
                    'student_classrooms.student_id',
                ])
                ->leftJoinSub($student_classroom, 'student_classrooms', function ($join) {
                    $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime as start',
                    'coach_classrooms.classroom_name as title',
                    'coach_classrooms.classroom_id',
                    'student_classrooms.id as student_classroom_id',
                    'student_classrooms.student_id',
                    'student_schedules.coach_schedule_id',
                    DB::raw("CASE
                        WHEN
                            student_schedules.coach_schedule_id IS NOT NULL AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 'primary'
                        WHEN coach_schedules.datetime::timestamp < (now()::timestamp + interval '6 hour')  THEN 'secondary'
                        ELSE 'success'
                    END color"),
                    DB::raw("CASE
                        WHEN student_schedules.coach_schedule_id IS NOT NULL AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 2
                        WHEN coach_schedules.datetime::timestamp < (now()::timestamp + interval '6 hour') AND
                            '$date'::timestamp < coach_schedules.datetime::timestamp
                            THEN 1
                        ELSE 3
                    END status"),
                    DB::raw("(
                        CASE
                            WHEN
                                coach_schedules.datetime::timestamp > now()::timestamp AND
                                student_schedules.coach_schedule_id IS NULL
                            THEN
                                1
                            WHEN
                                coach_schedules.datetime::timestamp <= now()::timestamp AND
                                student_schedules.coach_schedule_id IS NULL
                            THEN
                                1
                            WHEN
                                coach_schedules.datetime::timestamp > now()::timestamp AND
                                student_schedules.coach_schedule_id IS NOT NULL AND
                                student_classrooms.student_id = '$student_id' AND
                                student_schedules.student_id = '$student_id'
                            THEN
                                1
                            WHEN
                                coach_schedules.datetime::timestamp <= now()::timestamp AND
                                student_schedules.coach_schedule_id IS NOT NULL AND
                                student_classrooms.student_id = '$student_id' AND
                                student_schedules.student_id = '$student_id'
                            THEN
                                1
                            ELSE
                                0
                        END
                    ) as show")
                ])
                ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->leftJoinSub($student_classroom, 'student_classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'student_classrooms.classroom_id');
                })
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('coach_schedules.id', '=', 'student_schedules.coach_schedule_id');
                })
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->where('coach_schedules.accepted',true)
                ->whereNull('coach_schedules.deleted_at')
                ->get();

            return response([
                "status" => 200,
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

    public function special_class()
    {
        try {
            /*
                status
                1 => Terlewat Tanggal
                2 => Booking
                3 => Tersedia
            */

            $date = date('Y-m-d H:i:s');
            $student_id = Auth::guard('student')->user()->id;

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classrooms.price',
                ])
                ->where('package_type',1)
                ->whereNull('deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.classroom_id',
                    'coach_classrooms.coach_id',
                    'classrooms.name as classroom_name',
                    'classrooms.price',
                ])
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->whereNull('coach_classrooms.deleted_at');

            $student_classroom = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    'student_classrooms.classroom_id',
                    'student_classrooms.student_id',
                ])
                ->whereNull('student_classrooms.deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.coach_schedule_id',
                    'student_schedules.student_classroom_id',
                    'student_classrooms.student_id',
                ])
                ->leftJoinSub($student_classroom, 'student_classrooms', function ($join) {
                    $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime as start',
                    'coach_classrooms.classroom_name as title',
                    'coach_classrooms.classroom_id',
                    'student_classrooms.id as student_classroom_id',
                    'student_classrooms.student_id',
                    'student_schedules.id as student_schedule_id',
                    DB::raw("CASE
                        WHEN
                            student_schedules.coach_schedule_id IS NOT NULL AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 'primary'
                        WHEN coach_schedules.datetime::timestamp < (now()::timestamp + interval '6 hour') THEN 'secondary'
                        ELSE 'success'
                    END color"),
                    DB::raw("CASE
                        WHEN student_schedules.coach_schedule_id IS NOT NULL AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 2
                        WHEN coach_schedules.datetime::timestamp < (now()::timestamp + interval '6 hour') AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 1
                        ELSE 3
                    END status"),
                    DB::raw("(
                        CASE
                            WHEN
                                coach_schedules.datetime::timestamp > now()::timestamp AND
                                student_schedules.coach_schedule_id IS NULL
                            THEN
                                1
                            WHEN
                                coach_schedules.datetime::timestamp <= now()::timestamp AND
                                student_schedules.coach_schedule_id IS NULL
                            THEN
                                1
                            WHEN
                                coach_schedules.datetime::timestamp > now()::timestamp AND
                                student_schedules.coach_schedule_id IS NOT NULL AND
                                student_classrooms.student_id = '$student_id' AND
                                student_schedules.student_id = '$student_id'
                            THEN
                                1
                            WHEN
                                coach_schedules.datetime::timestamp <= now()::timestamp AND
                                student_schedules.coach_schedule_id IS NOT NULL AND
                                student_classrooms.student_id = '$student_id' AND
                                student_schedules.student_id = '$student_id'
                            THEN
                                1
                            ELSE
                                0
                        END
                    ) as show")
                ])
                ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->leftJoinSub($student_classroom, 'student_classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'student_classrooms.classroom_id');
                })
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('coach_schedules.id', '=', 'student_schedules.coach_schedule_id');
                })
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->where('coach_schedules.accepted',true)
                ->whereNull('coach_schedules.deleted_at')
                ->get();

            return response([
                "status" => 200,
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

    public function coach_schedule($id)
    {
        try {
            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classrooms.price',
                    'classrooms.session_total',
                    'classrooms.package_type',
                ])
                ->whereNull('deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.classroom_id',
                    'coach_classrooms.coach_id',
                    'classrooms.name as classroom_name',
                    'classrooms.price',
                    'classrooms.package_type',
                    'classrooms.session_total',
                    'coaches.name as coach_name',
                ])
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                ->whereNull('coach_classrooms.deleted_at');

            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime as start',
                    'coach_schedules.platform_link',
                    'coach_schedules.platform_id',
                    'coach_classrooms.classroom_name as title',
                    'coach_classrooms.coach_name',
                    'coach_classrooms.package_type',
                    'coach_classrooms.session_total',
                    'coach_classrooms.classroom_id',
                    'platforms.name as platform_name',
                ])
                ->joinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->join('platforms','coach_schedules.platform_id','=','platforms.id')
                ->where([
                    'coach_schedules.accepted' => true,
                    'coach_schedules.id' => $id
                ])
                ->whereNull('coach_schedules.deleted_at')
                ->first();

            $classroom = Classroom::find($result->classroom_id);

            $student_classroom = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    'transactions.datetime'
                ])
                ->join('transactions','student_classrooms.transaction_id','=','transactions.id')
                ->where([
                    'student_classrooms.student_id' => Auth::guard('student')->user()->id,
                    'student_classrooms.classroom_id' => $result->classroom_id,
                    'transactions.status' => 2
                ])
                ->whereNull([
                    'student_classrooms.deleted_at',
                    'transactions.deleted_at'
                ]);

            $count = DB::table('student_schedules')
                ->joinSub($student_classroom, 'student_classrooms', function ($join) {
                    $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
                })
                ->whereNotNull('student_schedules.deleted_at')
                ->count();

            if($classroom->package_type == 2 ){
                if($count >= 3){
                    $result->status_reschedule = 2;
                }
                else{
                    $result->status_reschedule = 1;
                }
            }
            else{
                if($count >= 1){
                    $result->status_reschedule = 2;
                }
                else{
                    $result->status_reschedule = 1;
                }
            }

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.student_classroom_id',
                ])
                ->whereNull('deleted_at');

            $student_schedule = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                ])
                ->joinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->where([
                    'student_classrooms.classroom_id' => $result->classroom_id,
                    'student_classrooms.student_id' => Auth::guard('student')->user()->id,
                ])
                ->whereNull('student_classrooms.deleted_at')
                ->whereNotNull('student_schedules.id')
                ->count();

            $date_transaction = $student_classroom->first();

            if($result->package_type == 2){
                if(
                    date('Y-m-d', strtotime($date_transaction->datetime . '+1 month')) > date('Y-m-d') &&
                    $student_schedule < (intval($result->session_total /2))
                ) {
                    $remaining = (intval($result->session_total/2)) - intval($student_schedule);
                }
                elseif(
                    date('Y-m-d', strtotime($date_transaction->datetime . '+2 month')) > date('Y-m-d') &&
                    $student_schedule < $result->session_total
                ) {
                    $remaining = (intval($result->session_total - $student_schedule))-(intval($result->session_total/2));
                }
                else{
                    $remaining = 0;
                }
            }else{
                if(
                    date('Y-m-d', strtotime($date_transaction->datetime . '+1 week')) > date('Y-m-d')
                ){
                    $remaining = (intval($result->session_total - $student_schedule));
                }
                else{
                    $remaining = 0;
                }
            }

            $result->remaining = $remaining;

            return response([
                "status" => 200,
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

    public function booking(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request) {

                $student_classroom = DB::table('coach_schedules')
                    ->select([
                        'student_classrooms.id',
                    ])
                    ->join('coach_classrooms','coach_schedules.coach_classroom_id','=','coach_classrooms.id')
                    ->join('student_classrooms', 'coach_classrooms.classroom_id', '=', 'student_classrooms.classroom_id')
                    ->where('coach_schedules.id',$request->coach_schedule_id)
                    ->where('student_classrooms.student_id',$request->student_id)
                    ->first();

                $check_session = DB::table('student_schedules')
                    ->whereNull('student_schedules.deleted_at')
                    ->where('student_schedules.student_classroom_id',$student_classroom->id)
                    ->count();

                $check_session+=1;

                $session = DB::table('sessions')
                    ->where([
                        'classroom_id' => $request->classroom_id,
                        'name' => $check_session
                    ])
                    ->whereNull('deleted_at')
                    ->first();
                if(empty($session)){
                    $session = new Session;
                    $session->classroom_id = $request->classroom_id;
                    $session->name = $check_session;
                    $session->save();
                }

                $student_schedule = StudentSchedule::create([
                    'student_classroom_id' => $student_classroom->id,
                    'session_id' => $session->id,
                    'coach_schedule_id' => $request->coach_schedule_id,
                ]);

                return $student_schedule;
            });

            return response([
                "status" => 200,
                "data"      => $result,
                "message"   => 'Successfully Booking!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function reschedule(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request) {
                $student_classroom = DB::table('coach_schedules')
                    ->select([
                        'student_classrooms.id',
                    ])
                    ->join('coach_classrooms','coach_schedules.coach_classroom_id','=','coach_classrooms.id')
                    ->join('student_classrooms', 'coach_classrooms.classroom_id', '=', 'student_classrooms.classroom_id')
                    ->where('coach_schedules.id',$request->coach_schedule_id)
                    ->where('student_classrooms.student_id',$request->student_id)
                    ->first();

                $student_schedule = StudentSchedule::where([
                    'student_classroom_id' => $student_classroom->id,
                    'coach_schedule_id' => $request->coach_schedule_id,
                ])->delete();

                return $student_schedule;
            });

            return response([
                "status" => 200,
                "data"      => $result,
                "message"   => 'Successfully Reschedule!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function new_reschedule(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request) {
                $student_classroom = DB::table('coach_schedules')
                    ->select([
                        'student_classrooms.id',
                    ])
                    ->join('coach_classrooms','coach_schedules.coach_classroom_id','=','coach_classrooms.id')
                    ->join('student_classrooms', 'coach_classrooms.classroom_id', '=', 'student_classrooms.classroom_id')
                    ->where('coach_schedules.id',$request->coach_schedule_id)
                    ->where('student_classrooms.student_id',$request->student_id)
                    ->first();

                $student_schedule = StudentSchedule::where([
                    'student_classroom_id' => $student_classroom->id,
                    'coach_schedule_id' => $request->coach_schedule_id,
                ])->delete();

                $student_classroom = DB::table('coach_schedules')
                    ->select([
                        'student_classrooms.id',
                    ])
                    ->join('coach_classrooms','coach_schedules.coach_classroom_id','=','coach_classrooms.id')
                    ->join('student_classrooms', 'coach_classrooms.classroom_id', '=', 'student_classrooms.classroom_id')
                    ->where('coach_schedules.id', $request->new_coach_schedule_id)
                    ->where('student_classrooms.student_id', $request->student_id)
                    ->first();

                $check_session = DB::table('student_schedules')
                    ->whereNull('student_schedules.deleted_at')
                    ->where('student_schedules.student_classroom_id', $student_classroom->id)
                    ->count();

                $check_session += 1;

                $session = DB::table('sessions')
                    ->where([
                        'classroom_id' => $request->classroom_id,
                        'name' => $check_session
                    ])
                    ->whereNull('deleted_at')
                    ->first();

                if(empty($session)){
                    $session = new Session;
                    $session->classroom_id = $request->classroom_id;
                    $session->name = $check_session;
                    $session->save();
                }

                $student_schedule = StudentSchedule::create([
                    'student_classroom_id' => $student_classroom->id,
                    'session_id' => $session->id,
                    'coach_schedule_id' => $request->new_coach_schedule_id,
                ]);

                return $student_schedule;
            });

            return response([
                "status" => 200,
                "data"      => $result,
                "message"   => 'Successfully Reschedule!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_total_class($student_id)
    {
        try {
            $result = DB::table('student_classrooms')
                ->leftJoin('classrooms','student_classrooms.classroom_id','=','classrooms.id')
                ->where('student_classrooms.student_id',$student_id)
                ->whereNull([
                    'student_classrooms.deleted_at',
                    'classrooms.deleted_at'
                ])
                ->count();

            return response([
                "status" => 200,
                "data"      => $result,
                "message"   => 'Successfully Reschedule!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function master_lesson()
    {
        try {
            /*
                status
                1 => Terlewat Tanggal
                2 => Tersedia
                3 => Penuh
            */

            $path = Storage::disk('s3')->url('/');

            $transaction = DB::table('transactions')
                ->select([
                    'transactions.id',
                    'transactions.status',
                ])
                ->where('transactions.status',2)
                ->whereNull('transactions.deleted_at');

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.cart_id',
                    'transaction_details.id',
                    'transactions.status',
                ])
                ->leftJoinSub($transaction, 'transactions', function ($join) {
                    $join->on('transaction_details.transaction_id', '=', 'transactions.id');
                })
                ->whereNull('transaction_details.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.master_lesson_id',
                    'transaction_details.id as transaction_detail_id',
                    'transaction_details.status'
                ])
                ->joinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('carts.id', '=', 'transaction_details.cart_id');
                })
                ->where([
                    'carts.student_id' => Auth::guard('student')->user()->id
                ])
                ->whereNull('carts.deleted_at')
                ->whereNotNull('transaction_details.id');

            $sub_master_lesson = DB::table('master_lessons')
                ->select([
                    'master_lessons.id',
                    DB::raw("COUNT(carts.master_lesson_id) as total_booking")
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('master_lessons.id', '=', 'carts.master_lesson_id');
                })
                ->groupBy('master_lessons.id');

            $master_lesson = DB::table('master_lessons')
                ->select([
                    'master_lessons.id',
                    'master_lessons.name',
                    DB::raw("CONCAT('{$path}',master_lessons.poster) as poster"),
                    'master_lessons.slot',
                    'master_lessons.platform_link',
                    'master_lessons.price',
                    'master_lessons.datetime',
                    'master_lessons.description',
                    'sub_master_lesson.total_booking',
                    DB::raw("(
                        CASE
                            WHEN master_lessons.datetime::timestamp < now()::timestamp THEN
                                1
                            WHEN (master_lessons.slot::numeric - sub_master_lesson.total_booking::numeric) = 0 THEN
                                3
                            ELSE
                                2
                        END
                    ) as status"),
                    DB::raw("(
                        CASE
                            WHEN master_lessons.datetime::timestamp < now()::timestamp THEN
                                'secondary'
                            WHEN (master_lessons.slot::numeric - sub_master_lesson.total_booking::numeric) = 0 THEN
                                'danger'
                            ELSE
                                'success'
                        END
                    ) as color"),
                    DB::raw("(
                        CASE
                            WHEN carts.status = 2 THEN
                                1
                            ELSE
                                0
                        END
                    )AS is_buy")
                ])
                ->leftJoinSub($sub_master_lesson, 'sub_master_lesson', function ($join) {
                    $join->on('master_lessons.id', '=', 'sub_master_lesson.id');
                })
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('master_lessons.id', '=', 'carts.master_lesson_id');
                })
                ->whereNull('master_lessons.deleted_at')
                ->get();

            return response([
                "status" => 200,
                "data"      => $master_lesson,
                "message"   => 'OK!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function coach_list_new(){
        try{
            $path = Storage::disk('s3')->url('/');

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.id',
                    'transaction_details.cart_id',
                    'transaction_details.transaction_id',
                ])
                ->Join('transactions','transaction_details.transaction_id','=','transactions.id')
                ->whereNotNull('transactions.id')
                ->where([
                    'transactions.status' => 2,
                    'transactions.student_id' => Auth::guard('student')->user()->id,
                ])
                ->whereNull([
                    'transaction_details.deleted_at',
                    'transactions.deleted_at',
                ]);

            $cart = DB::table('carts')
                ->select([
                    'carts.id',
                    'carts.classroom_id',
                    'transaction_details.id as transaction_detail_id',
                ])
                ->joinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('carts.id', '=', 'transaction_details.cart_id');
                })
                ->where('student_id', Auth::guard('student')->user()->id)
                ->whereNotNull('transaction_details.id')
                ->whereNull('carts.deleted_at');

            $classroom_feedback = DB::table('classroom_feedback')
                ->select([
                    'classroom_feedback.classroom_id',
                    'classroom_feedback.star',
                ])
                ->where('classroom_feedback.deleted_at');

            $sub_classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    DB::raw('round(AVG(classroom_feedback.star),1) as rating_classroom'),
                    DB::raw('(COUNT(classroom_feedback.classroom_id)) as total_review_classroom')
                ])
                ->leftJoinSub($classroom_feedback, 'classroom_feedback', function ($join) {
                    $join->on('classrooms.id', '=', 'classroom_feedback.classroom_id');
                })
                ->whereNull('classrooms.deleted_at')
                ->whereNotNull('classroom_feedback.star')
                ->groupBy('classrooms.id');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'carts.id as cart_id',
                    DB::raw('
                        CASE
                            WHEN sub_classroom.total_review_classroom is not null THEN
                                sub_classroom.total_review_classroom::integer
                            ELSE
                                0::integer
                        END AS total_review_classroom
                    '),
                    DB::raw('
                        CASE
                            WHEN sub_classroom.rating_classroom is not null THEN
                                sub_classroom.rating_classroom::integer
                            ELSE
                                0::integer
                        END AS rating_classroom
                    '),
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('classrooms.id', '=', 'carts.classroom_id');
                })
                ->leftJoinSub($sub_classroom, 'sub_classroom', function ($join) {
                    $join->on('classrooms.id', '=', 'sub_classroom.id');
                })
                ->whereNotNull('carts.transaction_detail_id')
                ->whereNull('classrooms.deleted_at');

            // session star

            $session_feedback = DB::table('session_feedback')
                ->select([
                    'session_feedback.student_schedule_id',
                    DB::raw('SUM(session_feedback.star)::integer as star')
                ])
                ->whereNull('session_feedback.deleted_at')
                ->whereNotNull('session_feedback.star')
                ->groupBy('session_feedback.student_schedule_id');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.coach_schedule_id',
                    DB::raw('
                        CASE
                            WHEN session_feedback.star is not null THEN
                                session_feedback.star::integer
                            ELSE
                                0
                        END AS star
                    '),
                ])
                ->leftJoinSub($session_feedback, 'session_feedback', function ($join) {
                    $join->on('student_schedules.id', '=', 'session_feedback.student_schedule_id');
                })
                ->leftJoin('student_classrooms','student_schedules.student_classroom_id','=','student_classrooms.id')
                ->whereNull('student_schedules.deleted_at')
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->whereNotNull('session_feedback.student_schedule_id');

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.coach_classroom_id',
                    'student_schedules.star',
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('coach_schedules.id', '=', 'student_schedules.coach_schedule_id');
                })
                ->whereNull('coach_schedules.deleted_at')
                ->whereNotNull('student_schedules.coach_schedule_id');

            // end session star

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.coach_id',
                    DB::raw('(SUM(classrooms.total_review_classroom)::integer + COUNT(coach_schedules.star)) as total_review'),
                    DB::raw('round(AVG(classrooms.rating_classroom),1) as rating_classroom'),
                    DB::raw('round(AVG(coach_schedules.star),1) as rating_schedule'),
                ])
                ->rightJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('coach_classrooms.id', '=', 'coach_schedules.coach_classroom_id');
                })
                ->whereNull('coach_classrooms.deleted_at')
                ->groupBy([
                    'coach_classrooms.coach_id',
                ]);

            // dd($coach_classroom->get());

            $result = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.email',
                    DB::raw("CONCAT('{$path}',coaches.image) as image"),
                    DB::raw("(
                        CASE
                            WHEN coach_classrooms.rating_schedule IS NOT NULL AND coach_classrooms.rating_classroom IS NOT NULL THEN
                                (coach_classrooms.rating_schedule::FLOAT + coach_classrooms.rating_classroom::FLOAT)/2
                            WHEN coach_classrooms.rating_schedule IS NOT NULL AND coach_classrooms.rating_classroom IS NULL THEN
                                coach_classrooms.rating_schedule::FLOAT
                            WHEN coach_classrooms.rating_schedule IS NULL AND coach_classrooms.rating_classroom IS NOT NULL THEN
                                coach_classrooms.rating_classroom::FLOAT
                            ELSE
                                0
                        END
                    )AS rating"),
                    'coaches.phone',
                    'coaches.name',
                    // DB::raw('(
                    //     CASE
                    //         WHEN coach_classrooms.class_active IS NOT NULL THEN
                    //             coach_classrooms.class_active
                    //         ELSE
                    //             0
                    //     END
                    // )as class_active'),
                    DB::raw('(
                        CASE
                            WHEN coach_classrooms.total_review IS NOT NULL THEN
                                coach_classrooms.total_review
                            ELSE
                                0
                        END
                    )as total_review'),
                ])
                ->rightJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coaches.id', '=', 'coach_classrooms.coach_id');
                })
                ->whereNull('coaches.deleted_at')
                ->get();

            return response([
                "status" => 200,
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

    public function coach_list()
    {
        try {
            $path = Storage::disk('s3')->url('/');
            // session star

            $session_feedback = DB::table('session_feedback')
                ->select([
                    'session_feedback.student_schedule_id',
                    'session_feedback.star',
                ])
                ->whereNull('session_feedback.deleted_at')
                ->whereNotNull('session_feedback.star');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.coach_schedule_id',
                    'session_feedback.star',
                    'student_classrooms.student_id',
                ])
                ->leftJoinSub($session_feedback, 'session_feedback', function ($join) {
                    $join->on('student_schedules.id', '=', 'session_feedback.student_schedule_id');
                })
                ->leftJoin('student_classrooms','student_schedules.student_classroom_id','=','student_classrooms.id')
                ->whereNull('student_schedules.deleted_at')
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->whereNotNull('session_feedback.student_schedule_id');

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.coach_classroom_id',
                    'student_schedules.star',
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('coach_schedules.id', '=', 'student_schedules.coach_schedule_id');
                })
                ->whereNull('coach_schedules.deleted_at')
                ->whereNotNull('student_schedules.coach_schedule_id');

            // end session star

            // classroom star

            $classroom_feedback = DB::table('classroom_feedback')
                ->select([
                    'classroom_feedback.classroom_id',
                    'classroom_feedback.star',
                ])
                ->where('classroom_feedback.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    DB::raw('round(AVG(classroom_feedback.star),1) as rating_classroom'),
                    DB::raw('(COUNT(classroom_feedback.classroom_id)) as total_review_classroom')
                ])
                ->leftJoinSub($classroom_feedback, 'classroom_feedback', function ($join) {
                    $join->on('classrooms.id', '=', 'classroom_feedback.classroom_id');
                })
                ->whereNull('classrooms.deleted_at')
                ->whereNotNull('classroom_feedback.star')
                ->groupBy('classrooms.id');

            // end classroom star

            $sub_coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.coach_classroom_id'
                ])
                ->whereDate('coach_schedules.datetime','>=',date('Y-m-d'))
                ->where('accepted',true)
                ->whereNull('coach_schedules.deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.coach_id',
                    DB::raw('(SUM(classrooms.total_review_classroom)::integer + COUNT(coach_schedules.star)) as total_review'),
                    DB::raw('round(AVG(classrooms.rating_classroom),1) as rating_classroom'),
                    DB::raw('round(AVG(coach_schedules.star),1) as rating_schedule'),
                    DB::raw('COUNT(sub_coach_schedules.coach_classroom_id) as class_active'),
                ])
                ->leftJoinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('coach_classrooms.id', '=', 'coach_schedules.coach_classroom_id');
                })
                ->leftJoinSub($sub_coach_schedule, 'sub_coach_schedules', function ($join) {
                    $join->on('coach_classrooms.id', '=', 'sub_coach_schedules.coach_classroom_id');
                })
                ->leftJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->whereNull('coach_classrooms.deleted_at')
                ->groupBy('coach_classrooms.coach_id');

            $result = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.email',
                    DB::raw("CONCAT('{$path}',coaches.image) as image"),
                    DB::raw("(
                        CASE
                            WHEN coach_classrooms.rating_schedule IS NOT NULL AND coach_classrooms.rating_classroom IS NOT NULL THEN
                                (coach_classrooms.rating_schedule::FLOAT + coach_classrooms.rating_classroom::FLOAT)/2
                            WHEN coach_classrooms.rating_schedule IS NOT NULL AND coach_classrooms.rating_classroom IS NULL THEN
                                coach_classrooms.rating_schedule::FLOAT
                            WHEN coach_classrooms.rating_schedule IS NULL AND coach_classrooms.rating_classroom IS NOT NULL THEN
                                coach_classrooms.rating_classroom::FLOAT
                            ELSE
                                0
                        END
                    )AS rating"),
                    'coaches.phone',
                    'coaches.name',
                    DB::raw('(
                        CASE
                            WHEN coach_classrooms.class_active IS NOT NULL THEN
                                coach_classrooms.class_active
                            ELSE
                                0
                        END
                    )as class_active'),
                    DB::raw('(
                        CASE
                            WHEN coach_classrooms.total_review IS NOT NULL THEN
                                coach_classrooms.total_review
                            ELSE
                                0
                        END
                    )as total_review'),
                ])
                ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coaches.id', '=', 'coach_classrooms.coach_id');
                })
                ->whereNull('coaches.deleted_at')
                ->get();

            return response([
                "status" => 200,
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

    public function student_rating()
    {
        try {
            $student_classroom = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    'student_classrooms.student_id',
                ]);

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_classrooms.student_id',
                ])
                ->leftJoinSub($student_classroom, 'student_classrooms', function ($join) {
                    $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
                });

            $result = DB::table('student_feedback')
                ->select([
                    DB::raw("(
                        CASE
                            WHEN round(AVG(student_feedback.star),1) IS NOT NULL THEN
                                round(AVG(student_feedback.star),1)
                            ELSE
                                0
                        END
                    ) as star")
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_feedback.student_schedule_id', '=', 'student_schedules.id');
                })
                ->where('student_schedules.student_id',Auth::guard('student')->user()->id)
                ->whereNull('student_feedback.deleted_at')
                ->groupBy('student_schedules.student_id')
                ->get();

            return response([
                "status" => 200,
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

    public function booking_master_lesson(Request $request,$id)
    {
        try{
            $is_exist = 0;
            $result = DB::transaction(function () use($request, $id){
                $check = Cart::where([
                    'master_lesson_id' => $id,
                    'student_id' => $request->student_id
                ])
                ->first();
                if($check)
                {
                    $is_exist = 1;
                }else{
                    $is_exist = 0;
                    $cart = Cart::create([
                            'master_lesson_id' => $id,
                            'student_id' => $request->student_id
                        ]);
                }

                return $is_exist;
            });
            if($is_exist == 1){
                return response([
                    "status" => 200,
                    "data"      => $result,
                    "message"   => 'Booking tersedia sebelumnya. Cek Keranjang Anda!'
                ], 200);
            }else{
                return response([
                    "status" => 200,
                    "data"      => $result,
                    "message"   => 'Sukses booking. Cek Keranjang Anda!'
                ], 200);
            }
        }catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function available_schedule($id)
    {
        try {


            return response([
                "status"  => 200,
                "data"    => $data,
                "message" => 'OK!'
            ],200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function list_reschedule(Request $request)
    {
        try {
            /*
                status
                1 => Terlewat Tanggal
                2 => Booking
                3 => Tersedia
            */

            // search student schedule

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.classroom_id'
                ])
                ->whereNull('deleted_at');

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'coach_schedules.coach_classroom_id',
                    'coach_classrooms.classroom_id',
                ])
                ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->whereNull('coach_schedules.deleted_at');

            $studentSchedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.*',
                    'coach_schedules.classroom_id',
                    'coach_schedules.datetime',
                ])
                ->leftJoinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->where([
                    'student_schedules.student_classroom_id' => $request->student_classroom_id,
                    'student_schedules.coach_schedule_id' => $request->coach_schedule_id,
                ])
                ->whereNull('student_schedules.deleted_at')
                ->first();

            // end search student schedule

            // add 3 day schedule before

            $date_now = date('Y-m-d');
            $max_date = date('Y-m-d', strtotime($studentSchedule->datetime. ' + 3 days'));
            $min_date = date('Y-m-d', strtotime($date_now . ' + 1 days'));

            // end add 3 day schedule before

            $date = date('Y-m-d H:i:s');
            $student_id = Auth::guard('student')->user()->id;

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classrooms.price',
                ])
                ->where([
                    'package_type' => 2,
                    'id' => $studentSchedule->classroom_id
                ])
                ->whereNull('deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.classroom_id',
                    'coach_classrooms.coach_id',
                    'classrooms.name as classroom_name',
                    'classrooms.price',
                    'coaches.name as coach_name',
                ])
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                ->where([
                    'coach_classrooms.classroom_id' => $studentSchedule->classroom_id
                ])
                ->whereNull([
                    'coach_classrooms.deleted_at',
                    'coaches.deleted_at',
                ]);

            $student_classroom = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    'student_classrooms.classroom_id',
                    'student_classrooms.student_id',
                ])
                ->where([
                    'student_classrooms.classroom_id' => $studentSchedule->classroom_id
                ])
                ->whereNull('student_classrooms.deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.coach_schedule_id',
                    'student_schedules.student_classroom_id',
                    'student_classrooms.student_id',
                ])
                ->leftJoinSub($student_classroom, 'student_classrooms', function ($join) {
                    $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime as start',
                    'coach_classrooms.classroom_name as title',
                    'coach_classrooms.classroom_id',
                    'student_classrooms.id as student_classroom_id',
                    'student_classrooms.student_id',
                    'student_schedules.coach_schedule_id',
                    'coach_classrooms.coach_name',
                    DB::raw("CASE
                        WHEN
                            student_schedules.coach_schedule_id IS NOT NULL AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 'primary'
                        WHEN coach_schedules.datetime::timestamp < (now()::timestamp + interval '6 hour')  THEN 'secondary'
                        ELSE 'success'
                    END color"),
                    DB::raw("CASE
                        WHEN student_schedules.coach_schedule_id IS NOT NULL AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 2
                        WHEN coach_schedules.datetime::timestamp < (now()::timestamp + interval '6 hour') AND
                            '$date'::timestamp < coach_schedules.datetime::timestamp
                            THEN 1
                        ELSE 3
                    END status"),
                    DB::raw("(
                        CASE
                            WHEN
                                coach_schedules.datetime::timestamp > now()::timestamp AND
                                student_schedules.coach_schedule_id IS NULL
                            THEN
                                1
                            WHEN
                                coach_schedules.datetime::timestamp <= now()::timestamp AND
                                student_schedules.coach_schedule_id IS NULL
                            THEN
                                1
                            WHEN
                                coach_schedules.datetime::timestamp > now()::timestamp AND
                                student_schedules.coach_schedule_id IS NOT NULL AND
                                student_classrooms.student_id = '$student_id' AND
                                student_schedules.student_id = '$student_id'
                            THEN
                                1
                            WHEN
                                coach_schedules.datetime::timestamp <= now()::timestamp AND
                                student_schedules.coach_schedule_id IS NOT NULL AND
                                student_classrooms.student_id = '$student_id' AND
                                student_schedules.student_id = '$student_id'
                            THEN
                                1
                            ELSE
                                0
                        END
                    ) as show")
                ])
                ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->leftJoinSub($student_classroom, 'student_classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'student_classrooms.classroom_id');
                })
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('coach_schedules.id', '=', 'student_schedules.coach_schedule_id');
                })
                ->where([
                    'student_classrooms.student_id' => Auth::guard('student')->user()->id,
                    'coach_schedules.accepted' => true,
                    'coach_classrooms.classroom_id' => $studentSchedule->classroom_id
                ])
                ->whereNull([
                    'coach_schedules.deleted_at',
                    'student_schedules.coach_schedule_id'
                ])
                ->whereNotIn('coach_schedules.id',[$request->coach_schedule_id])
                ->whereBetween('coach_schedules.datetime',[$min_date,$max_date])
                ->orderBy('coach_schedules.datetime','ASC')
                ->get();

            return response([
                "status"  => 200,
                "data"    => $result,
                "student_schedule" => $studentSchedule,
                "message" => 'OK!'
            ],200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
}
