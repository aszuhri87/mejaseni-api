<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Storage;
use Auth;
use DataTables;

class ReviewController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Review'
            ],
        ];

        return view('student.review.index', [
            'title' => 'Review',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }

    public function dt()
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name as classroom_name',
                ]);

            $session = DB::table('sessions')
                ->select([
                    'sessions.id',
                    'classrooms.classroom_name',
                ])
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('sessions.classroom_id', '=', 'classrooms.id');
                });

            $assignment = DB::table('assignments')
                ->select([
                    'assignments.id',
                    'sessions.classroom_name',
                ])
                ->joinSub($session, 'sessions', function ($join) {
                    $join->on('assignments.session_id', '=', 'sessions.id');
                });

            // collection
            $coach = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.name',
                    DB::raw("CONCAT('{$path}',coaches.image) as coach_image"),
                ]);

            $collection_feedback = DB::table('collection_feedback')
                ->select([
                    'collection_feedback.id',
                    'collection_feedback.collection_id',
                    'collection_feedback.star',
                    'collection_feedback.description',
                    'collection_feedback.updated_at',
                    'coaches.id as coach_id',
                    'coaches.name as coach_name',
                    'coaches.coach_image',
                ])
                ->joinSub($coach, 'coaches', function ($join) {
                    $join->on('collection_feedback.coach_id', '=', 'coaches.id');
                });

            $result = DB::table('collections')
                ->select([
                    'collections.name as collection_name',
                    'collection_feedback.id as collection_feedback_id',
                    'collection_feedback.star',
                    'collection_feedback.updated_at',
                    'collection_feedback.description',
                    'collection_feedback.coach_id',
                    'collection_feedback.coach_name',
                    'collection_feedback.coach_image',
                    'assignments.classroom_name',
                ])
                ->joinSub($collection_feedback, 'collection_feedback', function ($join) {
                    $join->on('collections.id', '=', 'collection_feedback.collection_id');
                })
                ->joinSub($assignment, 'assignments', function ($join) {
                    $join->on('collections.assignment_id', '=', 'assignments.id');
                })
                ->where('collections.student_id',Auth::guard('student')->user()->id)
                ->whereNull('collections.deleted_at')
                ->orderBy('collection_feedback.updated_at','DESC')
                ->get();

            return DataTables::of($result)
                ->addIndexColumn()
                ->make(true);

        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function dt_new(){
        try {
            $path = Storage::disk('s3')->url('/');

            $coach = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.name',
                    DB::raw("CONCAT('{$path}',coaches.image) as coach_image"),
                ]);

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name as classroom_name',
                ]);

            $student = DB::table('students')
                ->select([
                    'students.id',
                ]);

            $student_classroom = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    'classrooms.classroom_name',
                    'students.id as student_id'
                ])
                ->leftJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoinSub($student, 'students', function ($join) {
                    $join->on('student_classrooms.student_id', '=', 'students.id');
                })
                ->whereNull('deleted_at')
                ->distinct('student_classrooms.classroom_id');

            $theory = DB::table('theories')
                ->select([
                    'theories.session_id',
                    'theories.name as theory_name',
                ])
                ->distinct('theories.session_id');

            $session = DB::table('sessions')
                ->select([
                    'sessions.id',
                    'theories.theory_name',
                ])
                ->leftJoinSub($theory, 'theories', function ($join) {
                    $join->on('sessions.id', '=', 'theories.session_id');
                });

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_classrooms.classroom_name',
                    'sessions.theory_name',
                    'student_classrooms.student_id',
                ])
                ->leftJoinSub($student_classroom, 'student_classrooms', function ($join) {
                    $join->on('student_schedules.student_classroom_id', '=', 'student_classrooms.id');
                })
                ->leftJoinSub($session, 'sessions', function ($join) {
                    $join->on('student_schedules.session_id', '=', 'sessions.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $result = DB::table('student_feedback')
                ->select([
                    'student_feedback.*',
                    'student_feedback.created_at as datetime',
                    'coaches.name as coach_name',
                    'coaches.coach_image',
                    'student_schedules.classroom_name',
                    'student_schedules.theory_name',
                    'student_schedules.student_id',
                ])
                ->leftJoinSub($coach, 'coaches', function ($join) {
                    $join->on('student_feedback.coach_id', '=', 'coaches.id');
                })
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_feedback.student_schedule_id', '=', 'student_schedules.id');
                })
                ->whereNull('student_feedback.deleted_at')
                ->where('student_schedules.student_id', Auth::guard('student')->user()->id)
                ->orderBy('student_feedback.created_at','DESC')
                ->get();

            return DataTables::of($result)
                ->addIndexColumn()
                ->make(true);

        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_review($id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $coach = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.name as coach_name',
                    DB::raw("CONCAT('{$path}',coaches.image) as coach_image"),
                ])
                ->whereNull('coaches.deleted_at');

            $result = DB::table('collection_feedback')
                ->select([
                    'collection_feedback.id',
                    'collection_feedback.star',
                    'collection_feedback.description',
                    'coaches.coach_name',
                    'coaches.coach_image',
                ])
                ->joinSub($coach, 'coaches', function ($join) {
                    $join->on('collection_feedback.coach_id', '=', 'coaches.id');
                })
                ->whereNull('collection_feedback.deleted_at')
                ->where('collection_feedback.id',$id)
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

