<?php

namespace App\Http\Controllers\Admin\Schedule;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\CoachSchedule;
use App\Models\MasterLesson;
use App\Models\CoachNotification;
use App\Models\StudentNotification;

use Storage;
use DataTables;
use Auth;

class ListController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Schedule List'
            ],
        ];

        return view('admin.schedule-list.index', [
            'title' => 'Schedule List',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt(Request $request)
    {
        try {
            $student_schedules = DB::table('student_schedules')
                ->whereNull('deleted_at');

            $coach_schedules = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'classrooms.name',
                    'coaches.name as coach',
                    DB::raw("CASE
                        WHEN datetime::timestamp < now()::timestamp THEN 'secondary'
                        WHEN coach_schedules.accepted = false THEN 'warning'
                        WHEN students.id IS NOT NULL THEN 'primary'
                        ELSE 'success'
                    END color"),
                    DB::raw("CASE
                        WHEN datetime::timestamp < now()::timestamp THEN 'Selesai'
                        WHEN coach_schedules.accepted = false THEN 'Menunggu Konfirmasi'
                        WHEN students.id IS NOT NULL THEN 'Dipesan'
                        ELSE 'Tersedia'
                    END status_text"),
                ])
                ->leftJoin('coach_classrooms','coach_schedules.coach_classroom_id','=','coach_classrooms.id')
                ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                ->leftJoin('classrooms','coach_classrooms.classroom_id','=','classrooms.id')
                ->leftJoinSub($student_schedules, 'student_schedules', function($join){
                    $join->on('student_schedules.coach_schedule_id','coach_schedules.id');
                })
                ->leftJoin('sessions','student_schedules.session_id','=','sessions.id')
                ->leftJoin('student_classrooms','student_classrooms.id','=','student_schedules.student_classroom_id')
                ->leftJoin('students','students.id','=','student_classrooms.student_id')
                ->whereNull('coach_schedules.deleted_at')
                ->whereNull('coach_schedules.deleted_at')
                ->get();

            return DataTables::of($coach_schedules)->addIndexColumn()->make(true);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}
