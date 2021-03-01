<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Session;
use App\Models\StudentSchedule;
use App\Models\Cart;

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
            // dd(Auth::guard('student')->user()->id);
            $date = date('Y-m-d H:i:s');

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
                ])
                ->whereNull('student_schedules.deleted_at');

            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime as start',
                    'coach_classrooms.classroom_name as title',
                    'coach_classrooms.classroom_id',
                    'student_classrooms.id as student_classroom_id',
                    'student_classrooms.student_id',
                    DB::raw("CASE
                        WHEN coach_schedules.datetime::timestamp < now()::timestamp THEN 'secondary'
                        WHEN
                            student_schedules.coach_schedule_id IS NOT NULL AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 'primary'
                        ELSE 'success'
                    END color"),
                    DB::raw("CASE
                        WHEN coach_schedules.datetime::timestamp < now()::timestamp AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 1
                        WHEN student_schedules.coach_schedule_id IS NOT NULL THEN 2
                        ELSE 3
                    END status")
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
                ->where(function($query){
                    $query->whereNull('student_schedules.coach_schedule_id')
                        ->orWhereRaw('student_schedules.student_classroom_id = student_classrooms.id');
                })
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
            // dd(Auth::guard('student')->user()->id);
            $date = date('Y-m-d H:i:s');

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
                ])
                ->whereNull('student_schedules.deleted_at');

            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime as start',
                    'coach_classrooms.classroom_name as title',
                    'coach_classrooms.classroom_id',
                    'student_classrooms.id as student_classroom_id',
                    'student_classrooms.student_id',
                    DB::raw("CASE
                        WHEN coach_schedules.datetime::timestamp < now()::timestamp THEN 'secondary'
                        WHEN
                            student_schedules.coach_schedule_id IS NOT NULL AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 'primary'
                        ELSE 'success'
                    END color"),
                    DB::raw("CASE
                        WHEN coach_schedules.datetime::timestamp < now()::timestamp AND
                            '$date'::timestamp < coach_schedules.datetime
                            THEN 1
                        WHEN student_schedules.coach_schedule_id IS NOT NULL THEN 2
                        ELSE 3
                    END status")
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
                ->where(function($query){
                    $query->whereNull('student_schedules.coach_schedule_id')
                        ->orWhereRaw('student_schedules.student_classroom_id = student_classrooms.id');
                })
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
                ])
                ->whereNull('deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.classroom_id',
                    'coach_classrooms.coach_id',
                    'classrooms.name as classroom_name',
                    'classrooms.price',
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

            $remaining = $result->session_total - $student_schedule;

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

    public function get_total_class($student_id)
    {
        try {
            $result = DB::table('student_classrooms')
                ->where('student_id',$student_id)
                ->whereNull('deleted_at')
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

            $cart = DB::table('carts')
                ->select([
                    'carts.id',
                    'carts.master_lesson_id',
                ])
                ->join('transaction_details','carts.id','=','transaction_details.cart_id')
                ->whereNull([
                    'carts.deleted_at',
                    'transaction_details.deleted_at',
                ]);

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
                ])
                ->leftJoinSub($sub_master_lesson, 'sub_master_lesson', function ($join) {
                    $join->on('master_lessons.id', '=', 'sub_master_lesson.id');
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

    public function coach_list()
    {
        try {
            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                ])
                ->whereNull('deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.coach_schedule_id',
                    'student_schedules.student_classroom_id',
                ])
                ->rightJoinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $result = DB::table('student_classrooms')
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->whereNull('deleted_at')
                ->rightJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
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
            $result = DB::table('student_feedback')
                ->select([
                    DB::raw('round(AVG(star),1) as star')
                ])
                ->where('student_feedback.student_id',Auth::guard('student')->user()->id)
                ->whereNull('deleted_at')
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
            $result = DB::transaction(function () use($request, $id){
                $cart = Cart::create([
                        'master_lesson_id' => $id,
                        'student_id' => $request->student_id
                    ]);

                return $cart;
            });
            return response([
                "status" => 200,
                "data"      => $result,
                "message"   => 'Successfully booking. Please check your cart!'
            ], 200);
        }catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}
