<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Storage;
use Auth;

class ScheduleController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Schedule'
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

        return view('student.schedule.index', [
            'title' => 'Schedule',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
            'data' => $data,
        ]);
    }

    public function regular_class()
    {
        try {
            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                    'classrooms.price',
                ])
                ->where('package_type',1)
                ->whereNull('deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.classroom_id',
                    'coach_classrooms.coach_id',
                    'classrooms.name as classroom_name',
                    'classrooms.price',
                ])
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->whereNull('coach_classrooms.deleted_at');

            $regular = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime as start',
                    'coach_classrooms.classroom_name as title',
                ])
                ->joinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->where('coach_schedules.accepted',true)
                ->whereNull('coach_schedules.deleted_at')
                ->get();

            return response([
                "status" => 200,
                "data"      => $regular,
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
