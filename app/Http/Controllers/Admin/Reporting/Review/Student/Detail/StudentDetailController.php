<?php

namespace App\Http\Controllers\Admin\Reporting\Review\Student\Detail;

use App\Http\Controllers\BaseMenu;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class StudentDetailController extends BaseMenu
{
    public function index($id)
    {
        $navigation = [
            [
                'title' => 'Reporting'
            ],
            [
                'title' => 'Review'
            ],
            [
                'title' => 'Student'
            ],
            [
                'title' => 'Detail'
            ],
        ];
        $student = Student::find($id);

        return view('admin.reporting.review.student.detail.index', [
            'title' => 'Detail',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
            'student' => $student,
        ]);
    }

    public function dt($id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            // $result = DB::table('session_feedback')
            //     ->select(
            //         'session_feedback.*',
            //         'students.name as student_name',
            //         'coach_schedules.id as coach_schedule_id',
            //         DB::raw("CONCAT('{$path}',students.image) as image_url"),
            //         DB::raw("CASE
            //             WHEN student_schedules IS NOT NULL THEN 'COMPLETED'
            //             ELSE 'NOT COMPLETED'
            //         END status"),
            //         DB::raw("CASE
            //             WHEN student_schedules IS NOT NULL THEN 'success'
            //             ELSE 'danger'
            //         END color"),
            //     )
            //     ->join('student_schedules', 'student_schedules.id', 'session_feedback.student_schedule_id')
            //     ->join('student_classrooms', 'student_classrooms.id', 'student_schedules.student_classroom_id')
            //     ->join('students', 'students.id', 'student_classrooms.student_id')
            //     ->join('coach_schedules', 'coach_schedules.id', 'student_schedules.coach_schedule_id')
            //     ->join('coach_classrooms', 'coach_classrooms.id', 'coach_schedules.coach_classroom_id')
            //     ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
            //     ->where([
            //         ['coaches.id', $id],
            //     ])
            //     ->WhereNull('session_feedback.deleted_at')
            //     ->get();
            $result = DB::table('student_feedback')
                ->select(
                    'student_feedback.*',
                    'coaches.name as coache_name',
                    'classrooms.name as classroom_name',
                    'classrooms.session_duration',
                    'coach_schedules.datetime',
                    'expertises.name as expertise_name',
                    DB::raw("CONCAT('{$path}',students.image) as image_url"),
                )
                ->join('student_schedules', 'student_schedules.id', 'student_feedback.student_schedule_id')
                ->join('student_classrooms', 'student_classrooms.id', 'student_schedules.student_classroom_id')
                ->leftjoin('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
                ->join('students', 'students.id', 'student_classrooms.student_id')
                ->join('coaches', 'coaches.id', 'student_feedback.coach_id')
                ->leftjoin('coach_schedules', 'coach_schedules.id', 'student_schedules.coach_schedule_id')

                ->leftjoin('expertises', 'expertises.id', 'coaches.expertise_id')
                ->where([
                    ['students.id', $id],
                ])
                ->WhereNull('student_feedback.deleted_at')
                ->get();

            return DataTables::of($result)->addIndexColumn()->make(true);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "status" => 400,
                "message" => $e->getMessage(),
            ]);
        }
    }
}
