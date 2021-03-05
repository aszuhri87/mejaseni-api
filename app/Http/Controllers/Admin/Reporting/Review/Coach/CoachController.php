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

        $data = DB::table('coaches')
            ->select([
                'coaches.id',
                'coaches.name',
                'classrooms.name as class_name',
                'expertises.name as expertise_name',
                DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
                DB::raw("COUNT(classrooms.id) as total_class"),
                DB::raw("ROUND(AVG(session_feedback.star), 1) as total_rate"),
            ])
            ->leftJoin('coach_classrooms', 'coach_classrooms.coach_id', 'coaches.id')
            ->leftJoin('classrooms', 'classrooms.id', 'coach_classrooms.classroom_id')
            ->leftJoin('coach_schedules', 'coach_schedules.coach_classroom_id', 'coach_classrooms.id')
            ->leftJoin('student_schedules', 'student_schedules.coach_schedule_id', 'coach_schedules.id')
            ->leftJoin('session_feedback', 'session_feedback.student_schedule_id', 'student_schedules.id')
            ->leftJoin('expertises', 'expertises.id', 'coaches.expertise_id')
            // ->Join('classroom_categories', 'classroom_categories.id', '=', 'classrooms.classroom_category_id')
            // ->Join('sub_classroom_categories', 'sub_classroom_categories.id', '=', 'classrooms.sub_classroom_category_id')
            ->whereNull([
                'coaches.deleted_at'
            ])
            ->groupBy('coaches.id', 'coaches.name', 'classrooms.name', 'expertises.name')
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
