<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ClassroomFeedback;
use App\Models\StudentSchedule;
use App\Models\CoachSchedule;
use App\Models\SessionFeedback;

use Auth;
use Storage;
use DataTables;
use Exception;

class MyClassController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'My Class'
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

        return view('student.my-class.index', [
            'title' => 'My Class',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
            'data' => $data,
        ]);
    }

    public function booking_dt()
    {
        try {
            $date = date('Y-m-d H:i:s');

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

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.student_classroom_id',
                    'coach_schedules.datetime',
                    'coach_schedules.coach_name',
                    'coach_schedules.coach_id',
                ])
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull([
                    'student_schedules.deleted_at',
                ]);

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
                    'student_classrooms.classroom_id',
                    'classrooms.name as classroom_name',
                    'student_schedules.coach_name',
                    'student_schedules.coach_id',
                    'student_schedules.datetime',
                    'classrooms.session_duration',
                    DB::raw("
                        (student_schedules.datetime::timestamp + (classrooms.session_duration * INTERVAL '1 MINUTES')) as datetime_interval
                    ")
                ])
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->whereNull('student_classrooms.deleted_at')
                ->whereRaw("(student_schedules.datetime::timestamp + (classrooms.session_duration * INTERVAL '1 MINUTES')) >= now()")
                ->orderBy('classrooms.name','asc')
                ->orderBy('student_schedules.datetime','asc')
                ->get();

            $classroom_id = null;
            $index = 0;
            foreach ($data as $key => $value) {
                if($value->classroom_id != $classroom_id){
                    $classroom_id = $value->classroom_id;
                    $index = 1;
                    $value->session = $index;
                    $index++;
                }else{
                    $value->session = $index;
                    $index++;
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function reschedule($id)
    {
        try {
            $result = DB::transaction(function () use($id) {
                $data = DB::table('student_schedules')
                    ->where('id',$id)
                    ->delete();

                return $data;
            });
            return response([
                "status"    => 200,
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

    public function last_class_dt()
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
                ->whereRaw("(student_schedules.datetime::timestamp + (classrooms.session_duration * INTERVAL '1 MINUTES')) <= now()")
                ->orderBy('student_schedules.datetime','desc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function review(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use($request,$id) {

                $session_feedback = SessionFeedback::create([
                    'student_id' => Auth::guard('student')->user()->id,
                    'star' => $request->rating,
                    'description' => $request->commentar,
                    'student_schedule_id' => $id,
                ]);

            });
            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'Successfully Rate!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function class_active($id)
    {
        try {
            date_default_timezone_set("Asia/Jakarta");

            $now = date('Y-m-d H:i:s');

            $path = Storage::disk('s3')->url('/');

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

            $classroom_feedback = DB::table('classroom_feedback')
                ->select([
                    'classroom_feedback.classroom_id'
                ])
                ->where('classroom_feedback.student_id',Auth::guard('student')->user()->id)
                ->whereNull('classroom_feedback.deleted_at')
                ->groupBy('classroom_feedback.classroom_id');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classrooms.session_total',
                    'classrooms.package_type',
                    DB::raw("CONCAT('{$path}',image) as image"),
                ])
                ->whereNull('classrooms.deleted_at');

            $transaction_classes = DB::table('transaction_details')
                ->select([
                    'classrooms.id',
                    'transactions.datetime'
                ])
                ->leftJoin('transactions', 'transactions.id','transaction_details.transaction_id')
                ->leftJoin('carts','carts.id','transaction_details.cart_id')
                ->join('classrooms','classrooms.id','carts.classroom_id')
                ->where('transactions.student_id', Auth::guard('student')->user()->id)
                ->where('transactions.status', 2)
                ->whereNull(['transactions.deleted_at']);

            $result = DB::table('student_classrooms')
                ->select([
                    'classrooms.id as classroom_id',
                    'classrooms.name as classroom_name',
                    'classrooms.image',
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
                    DB::raw('(
                        CASE
                            WHEN classroom_feedback.classroom_id IS NOT NULL THEN
                                1
                            ELSE
                                0
                        END
                    )as is_rating')
                ])
                ->leftJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoinSub($sub_student_classroom, 'sub_student_classroom', function ($join) {
                    $join->on('student_classrooms.id', '=', 'sub_student_classroom.id');
                })
                ->leftJoinSub($classroom_feedback, 'classroom_feedback', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classroom_feedback.classroom_id');
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

    public function rating(Request $request)
    {
        try {
            $result = DB::transaction(function () use($request) {
                $rating = ClassroomFeedback::updateOrCreate([
                    'student_id' => Auth::guard('student')->user()->id,
                    'classroom_id' => $request->classroom_id,
                ],
                [
                    'star' => $request->rating_class,
                    'description' => $request->description,
                ]);
            });
            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'Successfully Rate!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function checkin($id)
    {
        try {
            $result = DB::transaction(function () use($id) {
                $checkin = StudentSchedule::find($id);

                if(empty($checkin->check_in)){
                    $checkin->update([
                        'check_in' => date('Y-m-d H:i:s')
                    ]);
                    $coach_schedule = CoachSchedule::find($checkin->coach_schedule_id);
                }else{
                    $coach_schedule = CoachSchedule::find($checkin->coach_schedule_id);
                }

                return $coach_schedule;
            });
            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'Successfully Rate!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_review($id)
    {
        try {
            $classroom = DB::table('classrooms')
                ->select([
                    'id',
                    'name',
                ]);

            $coach = DB::table('coaches')
                ->select([
                    'id',
                    'name'
                ]);

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coaches.name as coach_name',
                    'classrooms.name as classroom_name',
                ])
                ->joinSub($coach, 'coaches', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                })
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                });

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'coach_classrooms.coach_name',
                    'coach_classrooms.classroom_name',
                ])
                ->joinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                });

            $session_feedback = DB::table('session_feedback')
                ->select([
                    'student_schedule_id',
                    'star',
                    'description'
                ])
                ->where('student_id',Auth::guard('student')->user()->id)
                ->whereNull('deleted_at');

            $result = DB::table('student_schedules')
                ->select([
                    'coach_schedules.coach_name',
                    'coach_schedules.datetime',
                    'coach_schedules.classroom_name',
                    'session_feedback.star',
                    'session_feedback.description',
                ])
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->leftJoinSub($session_feedback, 'session_feedback', function ($join) {
                    $join->on('student_schedules.id', '=', 'session_feedback.student_schedule_id');
                })
                ->where('student_schedules.id',$id)
                ->first();

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
}
