<?php

namespace App\Http\Controllers\Admin\Reporting\Review\Coach;

use App\Http\Controllers\BaseMenu;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CoachController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Reporting'
            ],
            [
                'title' => 'Review'
            ],
            [
                'title' => 'Coach'
            ],
        ];

        return view('admin.reporting.review.coach.index', [
            'title' => 'Coach',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');

        $classroom = DB::table('classrooms')
            ->select([
                'classrooms.id',
                'classrooms.name',
            ])
            ->whereNull('classrooms.deleted_at');

        $coach_classroom = DB::table('coach_classrooms')
            ->select([
                'coach_classrooms.id',
                'coach_classrooms.coach_id',
                'classrooms.name',
            ])
            ->joinSub($classroom, 'classrooms', function ($join) {
                $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
            })
            ->whereNull('coach_classrooms.deleted_at');

        $session_feedback = DB::table('session_feedback')
            ->select([
                'session_feedback.student_schedule_id',
                'session_feedback.star'
            ])
            ->whereNull('session_feedback.deleted_at');

        $student_schedule = DB::table('student_schedules')
            ->select([
                'student_schedules.id',
                'student_schedules.coach_schedule_id',
                'session_feedback.star'
            ])
            ->leftJoinSub($session_feedback, 'session_feedback', function ($join) {
                $join->on('student_schedules.id', '=', 'session_feedback.student_schedule_id');
            })
            ->whereNull('student_schedules.deleted_at');

        $coach_schedule = DB::table('coach_schedules')
            ->select([
                'coach_schedules.id',
                'coach_schedules.coach_classroom_id',
                'student_schedules.star',
            ])
            ->joinSub($student_schedule, 'student_schedules', function ($join) {
                $join->on('coach_schedules.id', '=', 'student_schedules.coach_schedule_id');
            })
            ->whereNull('coach_schedules.deleted_at');

        $data = DB::table('coaches')
            ->select([
                'coaches.id',
                'coaches.name',
                'coach_classrooms.name as class_name',
                'expertises.name as expertise_name',
                DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
                DB::raw("COUNT(coach_classrooms.name) as total_class"),
                DB::raw("ROUND(AVG(coach_schedules.star), 1) as total_rate"),
            ])
            ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                $join->on('coaches.id', '=', 'coach_classrooms.coach_id');
            })
            ->leftJoinSub($coach_schedule, 'coach_schedules', function ($join) {
                $join->on('coach_classrooms.id', '=', 'coach_schedules.coach_classroom_id');
            })
            ->leftJoin('expertises', 'expertises.id', 'coaches.expertise_id')
            ->whereNull([
                'coaches.deleted_at'
            ])
            ->groupBy('coaches.id', 'coaches.name', 'coach_classrooms.name', 'expertises.name')
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function detail_review_coach($id)
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
}
