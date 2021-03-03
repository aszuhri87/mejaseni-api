<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\BaseMenu;
use App\Models\Coach;
use App\Models\CoachClassroom;
use App\Models\StudentFeedback;
use App\Models\StudentSchedule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Coach'
            ],
            [
                'title' => 'Dashboard'
            ],
        ];

        return view('coach.dashboard.index', [
            'title' => 'Dashboard',
            'navigation' => $navigation,
            'list_menu' => $this->menu_coach(),
        ]);
    }

    public function summary_course_chart()
    {
        try {
            date_default_timezone_set("Asia/Jakarta");

            $coaches = DB::table('coaches')
                ->select(
                    'coaches.*',
                )
                ->where([
                    ['coaches.id', Auth::guard('coach')->id()],
                ])
                ->WhereNull('coaches.deleted_at');

            $coach_classrooms = DB::table('coach_classrooms')
                ->select(
                    'coach_classrooms.*'
                )
                ->WhereNull('coach_classrooms.deleted_at')
                ->leftJoinSub($coaches, 'coaches', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                });

            $classrooms = DB::table('classrooms')
                ->select(
                    'classrooms.id',
                    'classrooms.name as classroom_name',
                    'classrooms.session_duration as classroom_session_duration',
                    'classrooms.package_type'
                )
                ->WhereNull('classrooms.deleted_at')
                ->leftJoinSub($coach_classrooms, 'coach_classrooms', function ($join) {
                    $join->on('classrooms.id', '=', 'coach_classrooms.classroom_id');
                });

                $transactions = DB::table('transactions')
                ->select(
                    'transactions.id',
                    'transactions.status',
                    'transactions.student_id',
                )
                ->where('status', 2);

            $students = DB::table('students')
                ->select(
                    'students.id',
                    'transactions.status',
                )
                ->WhereNull('students.deleted_at')
                ->JoinSub($transactions, 'transactions', function ($join) {
                    $join->on('students.id', '=', 'transactions.student_id');
                });

            $student_classrooms = DB::table('student_classrooms')
                ->select(
                    'student_classrooms.id',
                    'student_classrooms.student_id',
                    'student_classrooms.classroom_id',
                    'students.status',
                )
                ->WhereNull('student_classrooms.deleted_at')
                ->leftJoinSub($classrooms, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoinSub($students, 'students', function ($join) {
                    $join->on('student_classrooms.student_id', '=', 'students.id');
                });

            $data = DB::table('student_schedules')
                ->select(
                    'student_schedules.check_in',
                    'student_schedules.created_at',
                    'student_classrooms.status'
                )
                ->whereRaw("
                    (SELECT EXTRACT(WEEK FROM current_date)) = (SELECT EXTRACT(WEEK FROM student_schedules.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(MONTH FROM current_date)) = (SELECT EXTRACT(MONTH FROM student_schedules.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(YEAR FROM current_date)) = (SELECT EXTRACT(YEAR FROM student_schedules.created_at))
                ")
                ->leftJoinSub($student_classrooms, 'student_classrooms', function ($join) {
                    $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
                })
                ->WhereNull('student_schedules.deleted_at')
                ->orderBy('student_schedules.created_at', 'asc')
                ->get();

            $range_time = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            $kelas_booking = [0, 0, 0, 0, 0, 0, 0];
            $kelas_dihadiri = [0, 0, 0, 0, 0, 0, 0];
            foreach ($data as $key => $value) {
                if (date('N', strtotime($value->created_at)) == 1) {
                    if ($value->status == 2) { //2 kelas booking success
                        $kelas_booking[0]++;
                    }
                    if ($value->check_in == null) { //check in not null is "Hadir"
                        $kelas_dihadiri[0]--;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 2) {
                    if ($value->status == 2) { //2 kelas booking success
                        $kelas_booking[1]++;
                    }
                    if ($value->check_in == null) { //check in not null is "Hadir"
                        $kelas_dihadiri[1]--;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 3) {
                    if ($value->status == 2) { //2 kelas booking success
                        $kelas_booking[2]++;
                    }
                    if ($value->check_in == null) { //check in not null is "Hadir"
                        $kelas_dihadiri[2]--;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 4) {
                    $kelas_booking[3]++;
                    if ($value->status == 2) { //2 kelas booking success
                        $kelas_booking[3]++;
                    }
                    if ($value->check_in == null) { //check in not null is "Hadir"
                        $kelas_dihadiri[3]--;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 5) {
                    if ($value->status == 2) { //2 kelas booking success
                        $kelas_booking[4]++;
                    }
                    if ($value->check_in == null) { //check in not null is "Hadir"
                        $kelas_dihadiri[5]--;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 6) {
                    if ($value->status == 2) { //2 kelas booking success
                        $kelas_booking[5]++;
                    }
                    if ($value->check_in == null) { //check in not null is "Hadir"
                        $kelas_dihadiri[5]--;
                    }
                } elseif (date('N', strtotime($value->created_at)) == 7) {
                    if ($value->status == 2) { //2 kelas booking success
                        $kelas_booking[6]++;
                    }
                    if ($value->check_in == null) { //check in not null is "Hadir"
                        $kelas_dihadiri[6]--;
                    }
                }
            }

            $result = [
                'range_time' => $range_time,
                'kelas_booking' => $kelas_booking,
                'kelas_dihadiri' => $kelas_dihadiri
            ];
            return response([
                "status" => 200,
                "data" => $result,
                "message"   => 'Successfully!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function side_summary_course()
    {
        try {
            $total_class = DB::table('classrooms')
                ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->where('coaches.id', Auth::guard('coach')->id())
                ->whereRaw("
                    (SELECT EXTRACT(WEEK FROM current_date)) = (SELECT EXTRACT(WEEK FROM classrooms.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(MONTH FROM current_date)) = (SELECT EXTRACT(MONTH FROM classrooms.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(YEAR FROM current_date)) = (SELECT EXTRACT(YEAR FROM classrooms.created_at))
                ")
                ->WhereNull('classrooms.deleted_at')
                ->count();

            $total_video = DB::table('theory_videos')
                ->join('session_videos', 'session_videos.id', 'theory_videos.session_video_id')
                ->join('coaches', 'coaches.id', 'session_videos.coach_id')
                ->whereRaw("
                    (SELECT EXTRACT(WEEK FROM current_date)) = (SELECT EXTRACT(WEEK FROM theory_videos.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(MONTH FROM current_date)) = (SELECT EXTRACT(MONTH FROM theory_videos.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(YEAR FROM current_date)) = (SELECT EXTRACT(YEAR FROM theory_videos.created_at))
                ")
                ->where('coaches.id', Auth::guard('coach')->id())
                ->WhereNull('theory_videos.deleted_at')
                ->count();

            $total_booking = DB::table('transactions')
                ->join('students', 'students.id', 'transactions.student_id')
                ->join('student_classrooms', 'student_classrooms.student_id', 'students.id')
                ->join('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
                ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->whereRaw("
                    (SELECT EXTRACT(WEEK FROM current_date)) = (SELECT EXTRACT(WEEK FROM transactions.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(MONTH FROM current_date)) = (SELECT EXTRACT(MONTH FROM transactions.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(YEAR FROM current_date)) = (SELECT EXTRACT(YEAR FROM transactions.created_at))
                ")
                ->where([
                    ['transactions.confirmed', true],
                    ['coaches.id', Auth::guard('coach')->id()]
                ])
                ->WhereNull('transactions.deleted_at')
                ->count();

            $total_riwayat_booking = DB::table('transactions')
                ->join('students', 'students.id', 'transactions.student_id')
                ->join('student_classrooms', 'student_classrooms.student_id', 'students.id')
                ->join('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
                ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->whereRaw("
                    (SELECT EXTRACT(WEEK FROM current_date)) = (SELECT EXTRACT(WEEK FROM transactions.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(MONTH FROM current_date)) = (SELECT EXTRACT(MONTH FROM transactions.created_at))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(YEAR FROM current_date)) = (SELECT EXTRACT(YEAR FROM transactions.created_at))
                ")
                ->where([
                    ['coaches.id', Auth::guard('coach')->id()]
                ])
                ->WhereNull('transactions.deleted_at')
                ->count();

            $result = [
                'total_kelas' => $total_class,
                'total_video' => $total_video,
                'total_booking' => $total_booking,
                'total_riwayat_booking' => $total_riwayat_booking,
                'total_tidak_hadir' => 0,
            ];

            return response([
                "status" => 200,
                "data" => $result,
                "message"   => 'Successfully!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function dt_last_class()
    {
        $students = DB::table('students')
            ->select(
                'students.id',
                'students.name as student_name'
            )
            ->WhereNull('students.deleted_at');

        $student_classrooms = DB::table('student_classrooms')
            ->select(
                'student_classrooms.id',
                'student_classrooms.classroom_id',
                'students.student_name',
            )
            ->JoinSub($students, 'students', function ($join) {
                $join->on('student_classrooms.student_id', '=', 'students.id');
            })
            ->WhereNull('student_classrooms.deleted_at');

        $classrooms = DB::table('classrooms')
            ->select(
                'classrooms.id',
                'classrooms.session_duration',
                'classrooms.name',
                'classrooms.package_type',
                'student_classrooms.student_name',
            )
            ->JoinSub($student_classrooms, 'student_classrooms', function ($join) {
                $join->on('classrooms.id', '=', 'student_classrooms.classroom_id');
            })
            ->WhereNull('classrooms.deleted_at');

        $coaches = DB::table('coaches')
            ->select(
                'coaches.id',
            )
            ->where([
                ['coaches.id', Auth::guard('coach')->id()],
            ])
            ->WhereNull('coaches.deleted_at');

        $coach_classrooms = DB::table('coach_classrooms')
            ->select(
                'coach_classrooms.id',
                'classrooms.session_duration',
                'classrooms.name',
                'classrooms.package_type',
                'classrooms.student_name',
            )
            ->JoinSub($coaches, 'coaches', function ($join) {
                $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
            })
            ->JoinSub($classrooms, 'classrooms', function ($join) {
                $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
            })
            ->WhereNull('coach_classrooms.deleted_at');

        $student_schedules = DB::table('student_schedules')
            ->select(
                'student_schedules.id',
                'student_schedules.coach_schedule_id',
                'student_schedules.check_in',
            )
            ->where([
                ['student_schedules.deleted_at', null]
            ])
            ->WhereNull('student_schedules.deleted_at');

        $data = DB::table('coach_schedules')
            ->select(
                'coach_schedules.*',
                'student_schedules.id as student_schedule_id',
                'coach_classrooms.session_duration',
                'coach_classrooms.name as class_name',
                'coach_classrooms.student_name',
                DB::raw("CASE WHEN coach_classrooms.package_type = 1 THEN 'Spacial Class' ELSE 'Regular class' END package_type"),
                DB::raw("to_char(coach_schedules.datetime, 'Day, DD Month YYYY') as date_class"),
                DB::raw("to_char(coach_schedules.datetime, 'HH24:MI') as start_datetime"),
                DB::raw("to_char(coach_schedules.datetime::timestamp + INTERVAL '1 MINUTES' * coach_classrooms.session_duration, 'HH24:MI AM') as end_datetime"),
                DB::raw("CASE WHEN student_schedules.check_in IS NOT NULL THEN 'Complete' ELSE 'Cancle'END status"),
                DB::raw("CASE WHEN student_schedules.check_in IS NOT NULL THEN 'success' ELSE 'danger'END color_status"),
            )
            ->JoinSub($coach_classrooms, 'coach_classrooms', function ($join) {
                $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
            })
            ->JoinSub($student_schedules, 'student_schedules', function ($join) {
                $join->on('coach_schedules.id', '=', 'student_schedules.coach_schedule_id');
            })
            ->where([
                ['accepted', true]
            ])
            ->WhereNull('coach_schedules.deleted_at')
            ->distinct()
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function closest_schedule()
    {
        try {

            $path = Storage::disk('s3')->url('/');

            $coaches = DB::table('coaches')
                ->select(
                    'coaches.*',
                )
                ->where([
                    ['coaches.id', Auth::guard('coach')->id()],
                ])
                ->WhereNull('coaches.deleted_at');

            $classrooms = DB::table('classrooms')
                ->select(
                    'classrooms.id',
                    'classrooms.name as classroom_name',
                    'classrooms.session_duration as classroom_session_duration',
                    'classrooms.package_type'
                )
                ->WhereNull('classrooms.deleted_at');

            $coach_classrooms = DB::table('coach_classrooms')
                ->select(
                    'coach_classrooms.id',
                    'classrooms.classroom_name',
                    'classrooms.classroom_session_duration',
                    'classrooms.package_type'
                )
                ->leftJoinSub($classrooms, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoinSub($coaches, 'coaches', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                })
                ->WhereNull('coach_classrooms.deleted_at');

            $coach_schedules = DB::table('coach_schedules')
                ->select(
                    'coach_schedules.id',
                    'coach_schedules.coach_classroom_id',
                    'coach_schedules.datetime',
                    'coach_schedules.accepted',
                    'coach_classrooms.classroom_name',
                    'coach_classrooms.classroom_session_duration',
                    'coach_classrooms.package_type',
                )
                ->where('accepted', true)
                ->leftJoinSub($coach_classrooms, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->WhereNull('coach_schedules.deleted_at');

            $student_schedules = DB::table('student_schedules')
                ->select(
                    'student_schedules.id',
                    'student_schedules.student_classroom_id',
                    'student_schedules.check_in',
                    'coach_schedules.classroom_name',
                    'coach_schedules.classroom_session_duration',
                    'coach_schedules.coach_classroom_id',
                    'coach_schedules.datetime',
                    'coach_schedules.package_type',
                    'coach_schedules.id as coach_schedule_id',
                )
                ->where([
                    ['student_schedules.deleted_at', null]
                ])
                ->JoinSub($coach_schedules, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->WhereNull('student_schedules.deleted_at');

            $student_classrooms = DB::table('student_classrooms')
                ->select(
                    'student_classrooms.id',
                    'student_classrooms.student_id',
                    'student_schedules.classroom_name',
                    'student_schedules.classroom_session_duration',
                    'student_schedules.coach_classroom_id',
                    'student_schedules.datetime',
                    'student_schedules.package_type',
                    'student_schedules.check_in',
                    'student_schedules.coach_schedule_id',
                )
                ->JoinSub($student_schedules, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->WhereNull('student_classrooms.deleted_at');

            $result = DB::table('students')
                ->select(
                    'student_classrooms.datetime',
                    'student_classrooms.classroom_session_duration as session_duration',
                    'student_classrooms.classroom_name as class_name',
                    'student_classrooms.check_in',
                    'student_classrooms.coach_schedule_id',
                    'students.name as student_name',
                    DB::raw("CONCAT('{$path}',students.image) as image_url"),
                    DB::raw("CASE WHEN student_classrooms.package_type = 1 THEN 'Spacial Class' ELSE 'Regular class' END package_type"),
                    DB::raw("CASE WHEN student_classrooms.check_in IS NOT NULL THEN 'Complete' ELSE 'Cancle'END status"),
                    DB::raw("CASE WHEN student_classrooms.check_in IS NOT NULL THEN 'success' ELSE 'danger'END color_status"),
                    DB::raw("to_char(student_classrooms.datetime, 'Day, DD Month YYYY') as date_class"),
                    DB::raw("to_char(student_classrooms.datetime, 'HH24:MI') as start_datetime"),
                    DB::raw("to_char(student_classrooms.datetime::timestamp + INTERVAL '1 MINUTES' * student_classrooms.classroom_session_duration, 'HH24:MI AM') as end_datetime"),
                )
                ->JoinSub($student_classrooms, 'student_classrooms', function ($join) {
                    $join->on('students.id', '=', 'student_classrooms.student_id');
                })
                ->WhereNull('students.deleted_at')
                ->orderby('student_classrooms.datetime', 'desc')
                ->distinct()
                ->take(5)
                ->get();

            return response([
                "status" => 200,
                "data" => $result,
                "message"   => 'Successfully!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function show_last_class($id, $student_schedule_id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $detail_schedules = DB::table('coach_schedules')
                ->select(
                    'coach_schedules.*',
                    'classrooms.session_duration',
                    'classrooms.name as class_name',
                    'students.name as student_name',
                )
                ->join('coach_classrooms', 'coach_classrooms.id', 'coach_schedules.coach_classroom_id')
                ->join('classrooms', 'classrooms.id', 'coach_classrooms.classroom_id')
                ->join('student_classrooms', 'student_classrooms.classroom_id', 'classrooms.id')
                ->join('students', 'students.id', 'student_classrooms.student_id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->where([
                    ['coaches.id', Auth::guard('coach')->id()],
                    ['coach_schedules.id', $id]
                ])
                ->WhereNull('coach_schedules.deleted_at')
                ->first();

            $coach_schedules = DB::table('coach_schedules')
                ->select(
                    'coach_schedules.id',
                    'coach_schedules.coach_classroom_id'
                )
                ->where([
                    ['coach_schedules.id', $detail_schedules->id]
                ])
                ->WhereNull('coach_schedules.deleted_at');

            $coach_classrooms = DB::table('coach_classrooms')
                ->select(
                    'coach_classrooms.*'
                )
                ->leftJoinSub($coach_schedules, 'coach_schedules', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->WhereNull('coach_classrooms.deleted_at');

            $coaches = DB::table('coaches')
                ->select(
                    'coaches.*',
                )
                ->where([
                    ['coaches.id', Auth::guard('coach')->id()],
                ])
                ->leftJoinSub($coach_classrooms, 'coach_classrooms', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                })
                ->WhereNull('coaches.deleted_at');



            $students = DB::table('students')
                ->select(
                    'students.id',
                    'students.name as student_name',
                    DB::raw("CONCAT('{$path}',students.image) as image_url"),
                )
                ->WhereNull('deleted_at');

            $classrooms = DB::table('classrooms')
                ->select(
                    'classrooms.name as classrooms_name',
                    'classrooms.id',
                )
                ->WhereNull('deleted_at');

            $student_classrooms = DB::table('student_classrooms')
                ->select(
                    'student_classrooms.id',
                    'student_classrooms.classroom_id',
                    'students.student_name',
                    'students.image_url',
                    'classrooms.classrooms_name',
                )
                ->JoinSub($students, 'students', function ($join) {
                    $join->on('student_classrooms.student_id', '=', 'students.id');
                })
                ->JoinSub($classrooms, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->WhereNull('student_classrooms.deleted_at');

            $student_schedules = DB::table('student_schedules')
                ->select(
                    'student_schedules.id',
                    'student_classrooms.student_name',
                    'student_classrooms.classrooms_name',
                    'student_classrooms.image_url',
                    'coach_schedules.id as coach_schedules_id'
                )
                ->where('id', $student_schedule_id)
                ->JoinSub($student_classrooms, 'student_classrooms', function ($join) {
                    $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
                })
                ->JoinSub($coach_schedules, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->where([
                    ['student_schedules.deleted_at', null]
                ])
                ->WhereNull('student_schedules.deleted_at');


            $feedback = DB::table('student_feedback')
                ->select(
                    'student_feedback.*',
                    'student_schedules.student_name',
                    'student_schedules.coach_schedules_id',
                    'student_schedules.image_url',

                )
                ->JoinSub($coaches, 'coaches', function ($join) {
                    $join->on('student_feedback.coach_id', '=', 'coaches.id');
                })
                ->JoinSub($student_schedules, 'student_schedules', function ($join) {
                    $join->on('student_feedback.student_schedule_id', '=', 'student_schedules.id');
                })
                ->WhereNull('student_feedback.deleted_at')
                ->get();

            $result = array_merge(['detail_schedules' => $detail_schedules, 'feedback' => $feedback]);

            return response([
                "status" => 200,
                "data" => $result,
                "message"   => 'Successfully!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function review_last_class(Request $request, $id)
    {
        try {

            $studentSchedule = StudentSchedule::where('coach_schedule_id', $id)->first();

            $result = StudentFeedback::create([
                'coach_id' => Auth::guard('coach')->id(),
                'star' => $request->rate,
                'description' => $request->feedback,
                'student_schedule_id' => $studentSchedule->id
            ]);

            DB::transaction(function () use ($request, $studentSchedule) {
                $result = StudentFeedback::create([
                    'coach_id' => Auth::guard('coach')->id(),
                    'star' => $request->rate,
                    'description' => $request->feedback,
                    'student_schedule_id' => $studentSchedule->id
                ]);
            });

            return response([
                "message"   => 'Successfully Feedback Student!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function coach_show_review_last_class($id, $student_schedule_id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $coach_schedules = DB::table('coach_schedules')
                ->select(
                    'coach_schedules.id',
                    'coach_schedules.coach_classroom_id'
                )
                ->where([
                    ['coach_schedules.id', $id]
                ])
                ->WhereNull('coach_schedules.deleted_at');

            $coach_classrooms = DB::table('coach_classrooms')
                ->select(
                    'coach_classrooms.*'
                )
                ->leftJoinSub($coach_schedules, 'coach_schedules', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->WhereNull('coach_classrooms.deleted_at');

            $coaches = DB::table('coaches')
                ->select(
                    'coaches.*',
                )
                ->where([
                    ['coaches.id', Auth::guard('coach')->id()],
                ])
                ->leftJoinSub($coach_classrooms, 'coach_classrooms', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                })
                ->WhereNull('coaches.deleted_at');



            $students = DB::table('students')
                ->select(
                    'students.id',
                    'students.name as student_name',
                    DB::raw("CONCAT('{$path}',students.image) as image_url"),
                )
                ->WhereNull('deleted_at');

            $classrooms = DB::table('classrooms')
                ->select(
                    'classrooms.name as classrooms_name',
                    'classrooms.id',
                )
                ->WhereNull('classrooms.deleted_at');

            $student_classrooms = DB::table('student_classrooms')
                ->select(
                    'student_classrooms.id',
                    'student_classrooms.classroom_id',
                    'students.student_name',
                    'students.image_url',
                    'classrooms.classrooms_name',
                )
                ->JoinSub($students, 'students', function ($join) {
                    $join->on('student_classrooms.student_id', '=', 'students.id');
                })
                ->JoinSub($classrooms, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->WhereNull('student_classrooms.deleted_at');

            $student_schedules = DB::table('student_schedules')
                ->select(
                    'student_schedules.id',
                    'student_classrooms.student_name',
                    'student_classrooms.classrooms_name',
                    'student_classrooms.image_url',
                    'coach_schedules.id as coach_schedules_id'
                )
                ->where([
                    ['student_schedules.deleted_at', null]
                ])
                ->where('student_schedules.id', $student_schedule_id)
                ->JoinSub($student_classrooms, 'student_classrooms', function ($join) {
                    $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
                })
                ->JoinSub($coach_schedules, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->WhereNull('student_schedules.deleted_at');


            $result = DB::table('student_feedback')
                ->select(
                    'student_feedback.*',
                    'student_schedules.student_name',
                    'student_schedules.coach_schedules_id',
                    'student_schedules.image_url',
                )
                ->JoinSub($coaches, 'coaches', function ($join) {
                    $join->on('student_feedback.coach_id', '=', 'coaches.id');
                })
                ->JoinSub($student_schedules, 'student_schedules', function ($join) {
                    $join->on('student_feedback.student_schedule_id', '=', 'student_schedules.id');
                })
                ->WhereNull('student_feedback.deleted_at')
                ->get();

            return response([
                "status" => 200,
                "data" => $result,
                "message"   => 'Successfully!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }
}
