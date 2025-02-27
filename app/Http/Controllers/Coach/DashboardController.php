<?php

namespace App\Http\Controllers\Coach;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Coach;
use App\Models\Income;
use App\Models\CoachClassroom;
use App\Models\CoachSchedule;
use App\Models\StudentFeedback;
use App\Models\StudentSchedule;

use Carbon\Carbon;
use Exception;
use DataTables;

class DashboardController extends BaseMenu
{
    public function index()
    {
        $bank_account = DB::table('bank_accounts')
            ->select([
                'bank',
                'bank_number',
                'name_account'
            ])
            ->where('coach_id', Auth::guard('coach')->user()->id)
            ->first();

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
            'bank_account' => $bank_account
        ]);
    }

    public function summary_course_chart()
    {
        try {
            date_default_timezone_set("Asia/Jakarta");

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.coach_id'
                ])
                ->leftJoin('coaches', 'coach_classrooms.coach_id', '=', 'coaches.id')
                ->whereNull('coach_classrooms.deleted_at');

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'coach_classrooms.coach_id',
                ])
                ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->whereNull('coach_schedules.deleted_at');

            $data = DB::table('student_schedules')
                ->select([
                    'coach_schedules.coach_id',
                    'coach_schedules.datetime',
                    'student_schedules.check_in',
                ])
                ->leftJoinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull('student_schedules.deleted_at')
                ->where('coach_schedules.coach_id', Auth::guard('coach')->user()->id)
                ->whereRaw("
                    (SELECT EXTRACT(WEEK FROM current_date)) = (SELECT EXTRACT(WEEK FROM coach_schedules.datetime))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(MONTH FROM current_date)) = (SELECT EXTRACT(MONTH FROM coach_schedules.datetime))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(YEAR FROM current_date)) = (SELECT EXTRACT(YEAR FROM coach_schedules.datetime))
                ")
                ->get();

            $range_time = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            $kelas_booking = [0, 0, 0, 0, 0, 0, 0];
            $kelas_dihadiri = [0, 0, 0, 0, 0, 0, 0];
            foreach ($data as $key => $value) {

                if (date('N', strtotime($value->datetime)) == 1) {
                    if (date('Y-m-d H:i:s', strtotime($value->datetime)) >= date('Y-m-d H:i:s')) {
                        $kelas_booking[0]++;
                    }
                    if ($value->check_in == null) {
                        $kelas_dihadiri[0]++;
                    }
                } elseif (date('N', strtotime($value->datetime)) == 2) {
                    if (date('Y-m-d H:i:s', strtotime($value->datetime)) >= date('Y-m-d H:i:s')) {
                        $kelas_booking[1]++;
                    }
                    if ($value->check_in == null) {
                        $kelas_dihadiri[1]++;
                    }
                } elseif (date('N', strtotime($value->datetime)) == 3) {
                    if (date('Y-m-d H:i:s', strtotime($value->datetime)) >= date('Y-m-d H:i:s')) {
                        $kelas_booking[2]++;
                    }
                    if ($value->check_in == null) {
                        $kelas_dihadiri[2]++;
                    }
                } elseif (date('N', strtotime($value->datetime)) == 4) {
                    if (date('Y-m-d H:i:s', strtotime($value->datetime)) >= date('Y-m-d H:i:s')) {
                        $kelas_booking[3]++;
                    }
                    if ($value->check_in == null) {
                        $kelas_dihadiri[3]++;
                    }
                } elseif (date('N', strtotime($value->datetime)) == 5) {
                    if (date('Y-m-d H:i:s', strtotime($value->datetime)) >= date('Y-m-d H:i:s')) {
                        $kelas_booking[4]++;
                    }
                    if ($value->check_in == null) {
                        $kelas_dihadiri[4]++;
                    }
                } elseif (date('N', strtotime($value->datetime)) == 6) {
                    if (date('Y-m-d H:i:s', strtotime($value->datetime)) >= date('Y-m-d H:i:s')) {
                        $kelas_booking[5]++;
                    }
                    if ($value->check_in == null) {
                        $kelas_dihadiri[5]++;
                    }
                } elseif (date('N', strtotime($value->datetime)) == 7) {
                    if (date('Y-m-d H:i:s', strtotime($value->datetime)) >= date('Y-m-d H:i:s')) {
                        $kelas_booking[6]++;
                    }
                    if ($value->check_in == null) {
                        $kelas_dihadiri[6]++;
                    }
                }
            }

            $result = [
                'range_time' => $range_time,
                'kelas_booking' => $kelas_booking,
                'kelas_dihadiri' => $kelas_dihadiri,
                'total_booking' => $data->count()
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

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.coach_id',
                ])
                ->whereNull('coach_classrooms.deleted_at');

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'coach_classrooms.coach_id',
                ])
                ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->whereNull('coach_schedules.deleted_at');

            $total_booking = DB::table('student_schedules')
                ->select([
                    'coach_schedules.coach_id',
                    'coach_schedules.datetime',
                ])
                ->leftJoinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull('student_schedules.deleted_at')
                ->where('coach_schedules.coach_id', Auth::guard('coach')->user()->id)
                ->whereRaw("(coach_schedules.datetime::timestamp) >= now()")
                ->whereRaw("
                    (SELECT EXTRACT(WEEK FROM current_date)) = (SELECT EXTRACT(WEEK FROM coach_schedules.datetime))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(MONTH FROM current_date)) = (SELECT EXTRACT(MONTH FROM coach_schedules.datetime))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(YEAR FROM current_date)) = (SELECT EXTRACT(YEAR FROM coach_schedules.datetime))
                ")
                ->count();

            $total_riwayat_booking = DB::table('student_schedules')
                ->select([
                    'coach_schedules.coach_id',
                    'coach_schedules.datetime',
                ])
                ->leftJoinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull('student_schedules.deleted_at')
                ->where('coach_schedules.coach_id', Auth::guard('coach')->user()->id)
                ->whereRaw("(coach_schedules.datetime::timestamp) <= now()")
                ->whereRaw("
                    (SELECT EXTRACT(WEEK FROM current_date)) = (SELECT EXTRACT(WEEK FROM coach_schedules.datetime))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(MONTH FROM current_date)) = (SELECT EXTRACT(MONTH FROM coach_schedules.datetime))
                ")
                ->whereRaw("
                    (SELECT EXTRACT(YEAR FROM current_date)) = (SELECT EXTRACT(YEAR FROM coach_schedules.datetime))
                ")
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

    public function dt_last_class(Request $request)
    {
        $students = DB::table('students')
            ->select(
                'students.id',
                'students.name as student_name'
            )
            ->WhereNull('students.deleted_at');

        $student_classroom = DB::table('student_classrooms')
            ->select(
                'student_classrooms.id',
                'student_classrooms.classroom_id',
                'students.student_name',
            )
            ->leftJoinSub($students, 'students', function ($join) {
                $join->on('student_classrooms.student_id', '=', 'students.id');
            })
            ->WhereNull('student_classrooms.deleted_at');

        $student_schedules = DB::table('student_schedules')
            ->select(
                'student_schedules.id',
                'student_schedules.check_in',
                'student_schedules.coach_schedule_id',
                'student_classrooms.student_name',
            )
            ->leftJoinSub($student_classroom, 'student_classrooms', function ($join) {
                $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
            })
            ->WhereNull('student_schedules.deleted_at');

        $coach = DB::table('coaches')
            ->select([
                'coaches.id',
                'coaches.name'
            ])
            ->whereNull('coaches.deleted_at');

        $classroom = DB::table('classrooms')
            ->select([
                'classrooms.id',
                'classrooms.name',
                'classrooms.session_duration',
                'classrooms.package_type',
            ])
            ->whereNull('classrooms.deleted_at');

        $coach_classroom = DB::table('coach_classrooms')
            ->select([
                'coach_classrooms.id',
                'coach_classrooms.coach_id',
                'coaches.name as coach_name',
                'classrooms.id as classroom_id',
                'classrooms.name as class_name',
                'classrooms.session_duration',
                'classrooms.package_type',
            ])
            ->leftJoinSub($coach, 'coaches', function ($join) {
                $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
            })
            ->leftJoinSub($classroom, 'classrooms', function ($join) {
                $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
            })
            ->whereNull('coach_classrooms.deleted_at');

        $data = DB::table('coach_schedules')
            ->select(
                'coach_schedules.*',
                'student_schedules.id as student_schedule_id',
                'coach_classrooms.session_duration',
                'coach_classrooms.classroom_id',
                'coach_classrooms.class_name',
                'student_schedules.student_name',
                DB::raw("CASE WHEN coach_classrooms.package_type = 1 THEN 'Spacial Class' ELSE 'Regular class' END package_type"),
                DB::raw("CASE
                                WHEN student_schedules.check_in IS NOT NULL THEN 'Complete'
                                WHEN coach_schedules.deleted_at IS NOT NULL THEN 'Cancel'
                                ELSE 'Cancel'
                            END status"),
                DB::raw("CASE
                                WHEN student_schedules.check_in IS NOT NULL THEN 'success'
                                WHEN coach_schedules.deleted_at IS NOT NULL THEN 'danger'
                                ELSE 'danger'
                            END color_status"),
                DB::raw("to_char(coach_schedules.datetime, 'Day, DD Month YYYY') as date_class"),
                DB::raw("to_char(coach_schedules.datetime, 'HH24:MI') as start_datetime"),
                DB::raw("to_char(coach_schedules.datetime::timestamp + INTERVAL '1 MINUTES' * coach_classrooms.session_duration, 'HH24:MI AM') as end_datetime"),
            )
            ->JoinSub($student_schedules, 'student_schedules', function ($join) {
                $join->on('coach_schedules.id', '=', 'student_schedules.coach_schedule_id');
            })
            ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
            })
            ->where('coach_schedules.accepted', true)
            ->where(function ($query) use ($request) {
                if (!empty($request->date_start)) {
                    $query->whereDate('coach_schedules.datetime', '>=', $request->date_start)
                        ->whereDate('coach_schedules.datetime', '<=', $request->date_end);
                }
            })
            ->where('coach_classrooms.coach_id', Auth::guard('coach')->user()->id)
            ->whereRaw('coach_schedules.datetime::timestamp <= now()')
            ->orderby('coach_schedules.created_at', 'desc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function closest_schedule()
    {
        try {

            $path = Storage::disk('s3')->url('/');

            $students = DB::table('students')
                ->select(
                    'students.id',
                    'students.name as student_name',
                    'students.image as student_image'
                )
                ->WhereNull('students.deleted_at');

            $student_classrooms = DB::table('student_classrooms')
                ->select(
                    'student_classrooms.id',
                    'student_classrooms.classroom_id',
                    'students.student_name',
                    'students.student_image',
                )
                ->JoinSub($students, 'students', function ($join) {
                    $join->on('student_classrooms.student_id', '=', 'students.id');
                })
                ->WhereNull('student_classrooms.deleted_at');

            $student_schedules = DB::table('student_schedules')
                ->select(
                    'student_schedules.id',
                    'student_schedules.check_in',
                    'student_schedules.coach_schedule_id',
                    'student_classrooms.student_name',
                    'student_classrooms.student_image',
                )
                ->JoinSub($student_classrooms, 'student_classrooms', function ($join) {
                    $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
                })
                ->WhereNull('student_schedules.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name as class_name',
                    'classrooms.session_duration',
                    'classrooms.package_type',
                ]);

            $coach = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.name as coach_name',
                ])
                ->where('coaches.id', Auth::guard('coach')->user()->id)
                ->whereNull('deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'classrooms.class_name',
                    'classrooms.session_duration',
                    'coaches.coach_name',
                    'classrooms.package_type',
                ])
                ->JoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->JoinSub($coach, 'coaches', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                })
                ->whereNull('coach_classrooms.deleted_at');

            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.*',
                    'student_schedules.id as student_schedule_id',
                    'student_schedules.student_name',
                    'student_schedules.check_in',
                    'coach_classrooms.session_duration',
                    'coach_classrooms.class_name',
                    DB::raw("CONCAT('{$path}',student_schedules.student_image) as image_url"),
                    DB::raw("CASE WHEN coach_classrooms.package_type = 1 THEN 'Spacial Class' ELSE 'Regular class' END package_type"),
                    DB::raw("CASE
                                WHEN student_schedules.check_in IS NOT NULL THEN 'Complete'
                                WHEN coach_schedules.deleted_at IS NOT NULL THEN 'Complete'
                                ELSE 'Cancel'
                            END status"),
                    DB::raw("CASE
                                WHEN student_schedules.check_in IS NOT NULL THEN 'success'
                                WHEN coach_schedules.deleted_at IS NOT NULL THEN 'success'
                                ELSE 'danger'
                            END color_status"),
                    DB::raw("to_char(coach_schedules.datetime, 'Day, DD Month YYYY') as date_class"),
                    DB::raw("to_char(coach_schedules.datetime, 'HH24:MI') as start_datetime"),
                    DB::raw("to_char(coach_schedules.datetime::timestamp + INTERVAL '1 MINUTES' * coach_classrooms.session_duration, 'HH24:MI AM') as end_datetime"),
                ])
                ->JoinSub($student_schedules, 'student_schedules', function ($join) {
                    $join->on('coach_schedules.id', '=', 'student_schedules.coach_schedule_id');
                })
                ->JoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->whereNull('coach_schedules.deleted_at')
                ->orderBy('coach_schedules.datetime', 'desc')
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

            $detail_schedules = DB::table('student_schedules')
                ->select(
                    'student_schedules.*',
                    'classrooms.session_duration',
                    'classrooms.name as class_name',
                    'students.name as student_name',
                    'students.id as student_id',
                    'coach_schedules.id as coach_schedule_id',
                )
                ->join('student_classrooms', 'student_classrooms.id', 'student_schedules.student_classroom_id')
                ->join('students', 'students.id', 'student_classrooms.student_id')
                ->join('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
                ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
                ->join('coach_schedules', 'coach_schedules.coach_classroom_id', 'coach_classrooms.id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->where([
                    ['coaches.id', Auth::guard('coach')->id()],
                    ['coach_schedules.id', $id],
                    ['student_schedules.id', $student_schedule_id]
                ])
                ->WhereNull('student_schedules.deleted_at')
                ->first();

            $feedback = DB::table('student_feedback')
                ->select(
                    'student_feedback.*',
                    'students.name as student_name',
                    'student_schedules.coach_schedule_id',
                    DB::raw("CONCAT('{$path}',students.image) as image_url")
                )
                ->join('student_schedules', 'student_schedules.id', 'student_feedback.student_schedule_id')
                ->join('student_classrooms', 'student_classrooms.id', 'student_schedules.student_classroom_id')
                ->join('students', 'students.id', 'student_classrooms.student_id')
                ->where('coach_id', Auth::guard('coach')->id())
                ->where('student_schedule_id', $student_schedule_id)
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

    public function review_last_class(Request $request, $id, $student_schedule_id)
    {
        try {
            $studentSchedule = StudentSchedule::select(
                'student_schedules.id',
                'students.id as student_id'
            )
                ->join('student_classrooms', 'student_classrooms.id', 'student_schedules.student_classroom_id')
                ->join('students', 'students.id', 'student_classrooms.student_id')
                ->join('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
                ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->where([
                    ['student_schedules.id', $student_schedule_id]
                ])
                ->WhereNull('student_schedules.deleted_at')
                ->first();

            DB::transaction(function () use ($request, $studentSchedule) {
                StudentFeedback::updateOrCreate([
                    'coach_id' => Auth::guard('coach')->id(),
                    'student_schedule_id' => $studentSchedule->id,
                ], [
                    'star' => $request->rate,
                    'description' => $request->feedback,
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

            $result = DB::table('session_feedback')
                ->select(
                    'session_feedback.*',
                    'students.name as student_name',
                    'coach_schedules.id as coach_schedule_id',
                    DB::raw("CONCAT('{$path}',students.image) as image_url"),
                )
                ->join('student_schedules', 'student_schedules.id', 'session_feedback.student_schedule_id')
                ->join('student_classrooms', 'student_classrooms.id', 'student_schedules.student_classroom_id')
                ->join('students', 'students.id', 'student_classrooms.student_id')
                ->join('coach_schedules', 'coach_schedules.id', 'student_schedules.coach_schedule_id')
                ->join('coach_classrooms', 'coach_classrooms.id', 'coach_schedules.coach_classroom_id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->where([
                    ['coaches.id', Auth::guard('coach')->id()],
                    ['coach_schedules.id', $id],
                    ['student_schedules.id', $student_schedule_id],
                ])
                ->WhereNull('session_feedback.deleted_at')
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

    public function cancle_schedule($id)
    {
        try {
            $result = CoachSchedule::find($id);

            DB::transaction(function () use ($result) {
                $result->delete();
            });

            if ($result->trashed()) {
                return response([
                    "message"   => 'Successfully Cancle!'
                ], 200);
            }
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function incomes_chart()
    {
        try {
            $incomes = Income::select(['created_at', 'amount'])
                ->where('coach_id', Auth::guard('coach')->id())
                ->whereYear('created_at', date('Y'))
                ->get()
                ->groupBy(function($income) {
                    return Carbon::parse($income->created_at)->format('m');
                });

            $incomeMonthly = [];
            $totalMonthly = [];

            foreach ($incomes as $key => $value) {
                $total = 0;
                foreach ($value as $item) {
                    $total += $item->amount;
                }

                $incomeMonthly[(int)$key] = $total;
                $totalMonthly[(int)$key] = count($value);
            }

            $month = (int)date('m');
            if($month - 3 < 1){
                $star = 1;
            }else{
                $star = $month - 3;
            }

            if($month + 3 > 12){
                $end = 12;
            }else{
                $end = $month + 3;
            }

            $monthArr = [];
            $totalArr = [];
            $incomeArr = [];
            $max = 0;

            for($i = $star; $i <= $end; $i++){
                if(!empty($totalMonthly[$i])){
                    if($incomeMonthly[$i] > $max){
                        $max = $incomeMonthly[$i];
                    }

                    $totalArr[] = $totalMonthly[$i];
                    $incomeArr[] = $incomeMonthly[$i];
                }else{
                    $totalArr[] = 0;
                    $incomeArr[] = 0;
                }

                $monthArr[] = date('M', strtotime(date("Y-$i-d")));
            }

            return response([
                "data" => [
                    "month" => $monthArr,
                    "total" => $totalArr,
                    "income" => $incomeArr,
                    "max" => $max,
                ],
                "status" => 200,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
}
