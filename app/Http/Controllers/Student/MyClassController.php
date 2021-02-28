<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
                ->whereNull('student_schedules.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                ])
                ->whereNull('deleted_at');

            $data = DB::table('student_classrooms')
                ->select([
                    'student_schedules.id',
                    'classrooms.name as classroom_name',
                    'student_schedules.coach_name',
                    'student_schedules.coach_id',
                    'student_schedules.datetime',
                ])
                ->rightJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->whereNull('student_classrooms.deleted_at')
                ->whereDate('student_schedules.datetime','>=',$date)
                ->orderBy('student_schedules.datetime','asc')
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

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.student_classroom_id',
                    'coach_schedules.datetime',
                    'student_schedules.coach_schedule_id',
                    'coach_schedules.coach_name',
                    'coach_schedules.coach_id',
                ])
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                ])
                ->whereNull('deleted_at');

            $data = DB::table('student_classrooms')
                ->select([
                    'student_schedules.id',
                    'student_schedules.coach_schedule_id',
                    'classrooms.name as classroom_name',
                    'student_schedules.coach_name',
                    'student_schedules.coach_id',
                    'student_schedules.datetime',
                ])
                ->rightJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->whereNull('student_classrooms.deleted_at')
                ->whereDate('student_schedules.datetime','<',$date)
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
            dd($request->all());
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
}
