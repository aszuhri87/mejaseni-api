<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;

use Auth;
use Storage;

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
                ->whereNull('coach_schedules.deleted_at');

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
                ->whereNull('coach_schedules.deleted_at');

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
                ->whereNull('student_schedules.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.session_duration',
                ])
                ->whereNull('classrooms.deleted_at');

            $data = DB::table('student_classrooms')
                ->select([
                    'student_schedules.coach_schedule_id',
                    'student_schedules.datetime',
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->where('student_classrooms.student_id', Auth::guard('student')->user()->id)
                ->where('student_classrooms.deleted_at')
                ->whereRaw("(student_schedules.datetime::timestamp + (classrooms.session_duration * INTERVAL '1 MINUTES')) <= now()")
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

    public function not_present()
    {
        try {
            $date = date('Y-m-d');

            $coaches = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.name',
                ])
                ->whereNull('coaches.deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.coach_id',
                    'coaches.name as coach_name',
                ])
                ->joinSub($coaches, 'coaches', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                })
                ->whereNull('coach_classrooms.deleted_at');

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'coach_classrooms.coach_name',
                    'coach_classrooms.coach_id',
                ])
                ->joinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->whereNull('coach_schedules.deleted_at');

            $session_feedback = DB::table('session_feedback')
                ->select([
                    'session_feedback.student_schedule_id',
                    'session_feedback.star',
                    'session_feedback.id',
                ])
                ->where('session_feedback.student_id',Auth::guard('student')->user()->id)
                ->whereNull('session_feedback.deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.student_classroom_id',
                    'student_schedules.check_in',
                    'coach_schedules.datetime',
                    'student_schedules.coach_schedule_id',
                    'coach_schedules.coach_name',
                    'coach_schedules.coach_id',
                    'session_feedback.star',
                    'session_feedback.id as session_feedback_id',
                ])
                ->leftJoinSub($session_feedback, 'session_feedback', function ($join) {
                    $join->on('student_schedules.id', '=', 'session_feedback.student_schedule_id');
                })
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classrooms.session_duration',
                ])
                ->whereNull('deleted_at');

            $data = DB::table('student_classrooms')
                ->select([
                    'student_schedules.id',
                    'student_schedules.coach_schedule_id',
                    'classrooms.name as classroom_name',
                    'classrooms.session_duration',
                    'student_schedules.coach_name',
                    'student_schedules.coach_id',
                    'student_schedules.datetime',
                    'student_schedules.session_feedback_id',
                    DB::raw("(
                        CASE
                            WHEN student_schedules.star IS NOT NULL THEN
                                1
                            ELSE
                                0
                        END
                    ) as is_review_session"),
                    DB::raw("(
                        CASE
                            WHEN student_schedules.check_in IS NOT NULL THEN
                                1
                            ELSE
                                0
                        END
                    ) as status_checkin"),
                    DB::raw("
                        (student_schedules.datetime::timestamp + (classrooms.session_duration * INTERVAL '1 MINUTES')) as datetime_interval
                    ")
                ])
                ->rightJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->whereNull('student_classrooms.deleted_at')
                ->whereNull('student_schedules.check_in')
                ->whereRaw("(student_schedules.datetime::timestamp + (classrooms.session_duration * INTERVAL '1 MINUTES')) <= now()")
                ->orderBy('student_schedules.datetime','desc')
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

    public function upcoming()
    {
        try {
            $date = date('Y-m-d H:i:s');
            $path = Storage::disk('s3')->url('/');

            $coaches = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.name',
                ])
                ->whereNull('coaches.deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.coach_id',
                    'coaches.name as coach_name',
                ])
                ->joinSub($coaches, 'coaches', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                })
                ->whereNull('coach_classrooms.deleted_at');

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'coach_schedules.platform_link',
                    'coach_classrooms.coach_name',
                    'coach_classrooms.coach_id',
                ])
                ->joinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->whereNull('coach_schedules.deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.student_classroom_id',
                    'coach_schedules.datetime',
                    'coach_schedules.coach_name',
                    'coach_schedules.coach_id',
                    'coach_schedules.platform_link as link',
                ])
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classrooms.session_duration',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image"),
                ])
                ->whereNull('deleted_at');

            $upcoming_classroom = DB::table('student_classrooms')
                ->select([
                    'classrooms.name',
                    'student_schedules.coach_name',
                    'student_schedules.datetime',
                    'classrooms.image',
                    'student_schedules.link',
                    DB::raw('(false) as is_master_lesson')
                ])
                ->rightJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->whereNull('student_classrooms.deleted_at')
                ->whereRaw("(student_schedules.datetime::timestamp) >= now()")
                ->orderBy('student_schedules.datetime','asc')
                ->limit(2)
                ->get();

            // dd($upcoming_classroom);
            // upcoming master lesson

            $master_lesson = DB::table('master_lessons')
                ->select([
                    'master_lessons.id',
                    'master_lessons.name',
                    'master_lessons.datetime',
                    'master_lessons.platform_link as link',
                    DB::raw("CONCAT('{$path}',master_lessons.poster) as image"),
                    DB::raw("('Guest Star') as coach_name"),
                ])
                ->whereNull('master_lessons.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.id',
                    'master_lessons.name',
                    'master_lessons.link',
                    'master_lessons.datetime',
                    'master_lessons.image',
                    'master_lessons.coach_name',
                ])
                ->leftJoinSub($master_lesson, 'master_lessons', function ($join) {
                    $join->on('carts.master_lesson_id', '=', 'master_lessons.id');
                })
                ->whereNotNull('carts.master_lesson_id')
                ->whereNull('carts.deleted_at');

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.transaction_id',
                    'carts.name',
                    'carts.link',
                    'carts.datetime',
                    'carts.image',
                    'carts.coach_name',
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('transaction_details.cart_id', '=', 'carts.id');
                })
                ->where('transaction_details.deleted_at');

            $upcoming_master_lesson = DB::table('transactions')
                ->select([
                    'transaction_details.name',
                    'transaction_details.link',
                    'transaction_details.datetime',
                    'transaction_details.image',
                    'transaction_details.coach_name',
                    DB::raw("(true) as is_master_lesson")
                ])
                ->rightJoinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('transactions.id', '=', 'transaction_details.transaction_id');
                })
                ->where('transactions.student_id',Auth::guard('student')->user()->id)
                ->whereNull('transactions.deleted_at')
                ->whereNotNull('transaction_details.datetime')
                ->whereRaw("(transaction_details.datetime::timestamp) >= now()")
                ->orderBy('transaction_details.datetime','asc')
                ->first();

            $data = [];

            foreach ($upcoming_classroom as $key => $value) {
                $data[] = $value;
            }
            if(!empty($upcoming_master_lesson)){
                $data [] = $upcoming_master_lesson;
            }

            $sorting = collect($data)->sortBy(['datetime', 'asc']);

            return response([
                "status"  => 200,
                "data"    => $sorting,
                "message" => 'OK!'
            ],200);
        }catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function my_course(Request $request)
    {
        try {
            /*
                type
                1 => classroom
                2 => video class
            */
            $path = Storage::disk('s3')->url('/');
            $data = [];

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.session_total',
                    'classrooms.name',
                    'classrooms.image',
                ])
                ->whereNull('classrooms.deleted_at');

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                ])
                ->whereNull('coach_schedules.deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.student_classroom_id',
                    'coach_schedules.datetime',
                ])
                ->leftJoinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereRaw('coach_schedules.datetime::timestamp < now()')
                ->whereNull('student_schedules.deleted_at');

            $sub_data = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    DB::raw('COUNT(student_schedules.student_classroom_id) as total'),
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->whereNull('student_classrooms.deleted_at')
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->groupBy('student_classrooms.id');

            $classroom = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    'student_classrooms.classroom_id',
                    'classrooms.session_total',
                    'classrooms.name as classroom_name',
                    'classrooms.image',
                    'student_schedules.datetime',
                    'sub_data.total',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image"),
                    DB::raw("(1) as type")
                ])
                ->joinSub($sub_data, 'sub_data', function ($join) {
                    $join->on('student_classrooms.id', '=', 'sub_data.id');
                })
                ->leftJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->whereNull('student_classrooms.deleted_at')
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->get();

            // video
            $sub_classroom_category = DB::table('sub_classroom_categories')
                ->select([
                    'sub_classroom_categories.id',
                    'sub_classroom_categories.image',
                ])
                ->whereNull('sub_classroom_categories.deleted_at');

            $session_video = DB::table('session_videos')
                ->select([
                    'session_videos.id',
                    'session_videos.name',
                    DB::raw("CONCAT('{$path}',sub_classroom_categories.image) as image"),
                ])
                ->leftJoinSub($sub_classroom_category, 'sub_classroom_categories', function ($join) {
                    $join->on('session_videos.sub_classroom_category_id', '=', 'sub_classroom_categories.id');
                })
                ->whereNull('session_videos.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.id',
                    'session_videos.id as session_video_id',
                    'session_videos.name',
                    'session_videos.image',
                ])
                ->leftJoinSub($session_video, 'session_videos', function ($join) {
                    $join->on('carts.session_video_id', '=', 'session_videos.id');
                })
                ->whereNotNull('carts.session_video_id');

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.transaction_id',
                    'carts.session_video_id',
                    'carts.name',
                    'carts.image',
                ])
                ->joinSub($cart, 'carts', function ($join) {
                    $join->on('transaction_details.cart_id', '=', 'carts.id');
                });

            $video = DB::table('transactions')
                ->select([
                    'transaction_details.session_video_id',
                    'transaction_details.name',
                    'transaction_details.image',
                    DB::raw('(2) as type')
                ])
                ->joinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('transactions.id', '=', 'transaction_details.transaction_id');
                })
                ->where('transactions.student_id',Auth::guard('student')->user()->id)
                ->get();

            if(!empty($request->filter_course)){
                if($request->filter_course == 0){
                    foreach ($classroom as $key => $value) {
                        $data[] = $value;
                    }
                    foreach ($video as $key => $value) {
                        $data[] = $value;
                    }
                }elseif($request->filter_course == 1){
                    foreach ($classroom as $key => $value) {
                        $data[] = $value;
                    }
                }elseif($request->filter_course == 2){
                    foreach ($video as $key => $value) {
                        $data[] = $value;
                    }
                }
            }else{
                foreach ($classroom as $key => $value) {
                    $data[] = $value;
                }
                foreach ($video as $key => $value) {
                    $data[] = $value;
                }
            }

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

    public function summary_course()
    {
        try {
            // present
            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                ])
                ->where('coach_schedules.accepted',true)
                ->whereNull('coach_schedules.deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.student_classroom_id',
                    'student_schedules.coach_schedule_id',
                    'coach_schedules.datetime',
                ])
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNotNull('student_schedules.check_in')
                ->whereRaw('coach_schedules.datetime::timestamp < now()')
                ->whereNull('student_schedules.deleted_at');

            $present = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    'student_schedules.datetime',
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->where('student_classrooms.student_id', Auth::guard('student')->user()->id)
                ->whereNull('student_classrooms.deleted_at')
                ->whereNotNull('student_schedules.coach_schedule_id')
                ->whereYear('student_schedules.datetime',date('Y'))
                ->get();

            // end present

            // booking
            $booking_coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'coach_schedules.accepted'
                ])
                ->where('coach_schedules.accepted',true)
                ->whereNull('coach_schedules.deleted_at');

            $booking_student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.student_classroom_id',
                    'coach_schedules.id as coach_schedule_id',
                    'coach_schedules.datetime',
                    'coach_schedules.accepted',
                ])
                ->leftJoinSub($booking_coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereRaw('coach_schedules.datetime::timestamp > now()')
                ->whereNull('student_schedules.deleted_at');

            $booking = DB::table('student_classrooms')
                ->select([
                    'student_schedules.coach_schedule_id',
                    'student_schedules.datetime',
                ])
                ->leftJoinSub($booking_student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->where('student_classrooms.student_id', Auth::guard('student')->user()->id)
                ->where('student_classrooms.deleted_at')
                ->whereNotNull('student_schedules.coach_schedule_id')
                ->whereYear('student_schedules.datetime',date('Y'))
                ->get();

            $cart_booking = [0,0,0,0,0,0,0,0,0,0,0,0];
            $cart_present = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach ($booking as $key => $value) {
                    if(date('m',strtotime($value->datetime)) == '01'){
                        $cart_booking[0]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '02'){
                        $cart_booking[1]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '03'){
                        $cart_booking[2]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '04'){
                        $cart_booking[3]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '05'){
                        $cart_booking[4]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '06'){
                        $cart_booking[5]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '07'){
                        $cart_booking[6]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '08'){
                        $cart_booking[7]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '09'){
                        $cart_booking[8]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '10'){
                        $cart_booking[9]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '11'){
                        $cart_booking[10]++;
                    }else{
                        $cart_booking[11]++;
                    }
                }

                foreach ($present as $key => $value) {
                    if(date('m',strtotime($value->datetime)) == '01'){
                        $cart_present[0]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '02'){
                        $cart_present[1]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '03'){
                        $cart_present[2]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '04'){
                        $cart_present[3]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '05'){
                        $cart_present[4]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '06'){
                        $cart_present[5]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '07'){
                        $cart_present[6]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '08'){
                        $cart_present[7]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '09'){
                        $cart_present[8]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '10'){
                        $cart_present[9]++;
                    }
                    elseif(date('m',strtotime($value->datetime)) == '11'){
                        $cart_present[10]++;
                    }else{
                        $cart_present[11]++;
                    }
                }
            $data = [
                'booking' =>$cart_booking,
                'present' =>$cart_present,
            ];

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

    public function student_booking_week()
    {
        try {
            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                ])
                ->whereNull('coach_schedules.deleted_at');

            $data = DB::table('student_schedules')
                ->select([
                    'student_schedules.coach_schedule_id'
                ])
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereRaw('(SELECT EXTRACT(WEEK FROM coach_schedules.datetime)) = (SELECT EXTRACT(WEEK FROM now()))')
                ->whereNull('student_schedules.deleted_at')
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

    public function progress_class()
    {
        try {
            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                ])
                ->whereNull('coach_schedules.deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.student_classroom_id',
                    'coach_schedules.datetime',
                ])
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                ])
                ->whereNull('classrooms.deleted_at');

            $sub_data = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    DB::raw('count(student_schedules.student_classroom_id) as total_present')
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->whereRaw("(student_schedules.datetime::timestamp + (classrooms.session_duration * INTERVAL '1 MINUTES')) <= now()")
                ->whereNull('student_classrooms.deleted_at')
                ->groupBy('student_classrooms.id');

            $data = DB::table('student_classrooms')
                ->select([
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    DB::raw('(
                        CASE
                            WHEN sub_data.total_present IS NOT NULL THEN
                                sub_data.total_present
                            ELSE
                                0
                        END
                    ) as total_present'),
                ])
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoinSub($sub_data, 'sub_data', function ($join) {
                    $join->on('student_classrooms.id', '=', 'sub_data.id');
                })
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->whereNull('student_classrooms.deleted_at')
                ->get();

                $total_class = 0;
                $total_present = 0;

                foreach ($data as $key => $value) {
                    $total_class++;
                    if($key == 0 ){
                        $total_present = ($value->total_present / $value->session_total)*100;
                    }
                    else{
                        $present = ($value->total_present / $value->session_total)*100;
                        $total_present+=$present;
                    }
                }

                if($total_class > 0){
                    $total_present = $total_present/$total_class;
                }

                $result = [
                    'total_class' => $total_class,
                    'total_present' => round($total_present,2),
                ];

            return response([
                "status"  => 200,
                "data"    => $result,
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
