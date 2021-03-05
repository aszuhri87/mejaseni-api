<?php

namespace App\Http\Controllers\Admin\Reporting\Review\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends BaseMenu
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
                'title' => 'Student'
            ],
        ];

        return view('admin.reporting.review.student.index', [
            'title' => 'Student',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        $path = Storage::disk('s3')->url('/');

        $data = DB::table('students')
            ->select([
                'students.id',
                'students.name',
                'expertise',
                DB::raw("CONCAT('{$path}',students.image) as image_url"),
                DB::raw("COUNT(student_classrooms.id) as total_class"),
                DB::raw("ROUND(AVG(student_feedback.star), 1) as total_rate"),
                DB::raw("CASE
                        WHEN expertise IS NOT NULL THEN expertise
                        ELSE '-'
                    END expertise_name"),
            ])
            ->leftJoin('student_classrooms', 'student_classrooms.student_id', 'students.id')
            // ->Join('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
            // ->Join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
            // ->Join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
            ->leftJoin('student_schedules', 'student_schedules.student_classroom_id', 'student_classrooms.id')
            ->leftJoin('student_feedback', 'student_feedback.student_schedule_id', 'student_schedules.id')
            // ->whereNull([
            //     'students.deleted_at'
            // ])
            ->groupBy('students.id', 'students.name','expertise')
            ->distinct()
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }
}
