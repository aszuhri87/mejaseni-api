<?php

namespace App\Http\Controllers\Admin\Reporting\Review\Coach\Detail;

use App\Http\Controllers\BaseMenu;
use App\Models\Coach;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CoachDetailController extends BaseMenu
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
                'title' => 'Coach'
            ],
            [
                'title' => 'Detail'
            ],
        ];
        $coach = Coach::find($id);

        return view('admin.reporting.review.coach.detail.index', [
            'title' => 'Detail',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
            'coach' => $coach,
        ]);
    }

    public function dt($id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $result = DB::table('session_feedback')
                ->select(
                    'session_feedback.*',
                    'students.name as student_name',
                    'coach_schedules.id as coach_schedule_id',
                    'classrooms.session_duration',
                    'coach_schedules.datetime',
                    DB::raw("CONCAT('{$path}',students.image) as image_url"),
                )
                ->join('student_schedules', 'student_schedules.id', 'session_feedback.student_schedule_id')
                ->join('student_classrooms', 'student_classrooms.id', 'student_schedules.student_classroom_id')
                ->leftjoin('classrooms', 'classrooms.id', 'student_classrooms.classroom_id')
                ->join('students', 'students.id', 'student_classrooms.student_id')
                ->join('coach_schedules', 'coach_schedules.id', 'student_schedules.coach_schedule_id')
                ->join('coach_classrooms', 'coach_classrooms.id', 'coach_schedules.coach_classroom_id')
                ->join('coaches', 'coaches.id', 'coach_classrooms.coach_id')
                ->where([
                    ['coaches.id', $id],
                ])
                ->WhereNull('session_feedback.deleted_at')
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

    public function get_classrooms($id)
    {
        try {
            $result = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classroom_category_id',
                    'sub_classroom_category_id',
                ])
                ->join('coach_classrooms', 'coach_classrooms.classroom_id', 'classrooms.id')
                ->where('coach_classrooms.coach_id', $id)
                ->whereNull('classrooms.deleted_at')
                ->distinct()
                ->orderBy('classrooms.name', 'asc')
                ->get();

            return response([
                "data"      => $result,
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
