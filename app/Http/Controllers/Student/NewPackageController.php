<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Storage;

class NewPackageController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Data Buy New Package'
            ],
        ];

        return view('student.new-package.index', [
            'title' => 'Buy New Package',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }

    public function get_package()
    {
        try {
            $path = Storage::disk('s3')->url('/');
            $coach = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.name as coach_name',
                ])
                ->whereNull('coaches.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name as classroom_name',
                    'classrooms.price',
                    'classrooms.description',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    DB::raw("CONCAT('{$path}',classrooms.image) as image_url"),
                ])
                ->whereNull('classrooms.deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.classroom_id',
                    'coach_classrooms.coach_id',
                    'coaches.coach_name',
                    'classrooms.classroom_name',
                    'classrooms.price',
                    'classrooms.description',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    'classrooms.image_url',
                ])
                ->leftJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('coach_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoinSub($coach, 'coaches', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                })
                ->whereNull('coach_classrooms.deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.coach_schedule_id',
                ])
                ->whereNull('student_schedules.deleted_at');

            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id as coach_schedule_id',
                    'coach_classrooms.classroom_id',
                    'coach_classrooms.classroom_id',
                    'coach_classrooms.coach_name',
                    'coach_classrooms.classroom_name',
                    'coach_classrooms.price',
                    'coach_classrooms.description',
                    'coach_classrooms.session_total',
                    'coach_classrooms.session_duration',
                    'coach_classrooms.image_url',
                    'coach_classrooms.coach_id',
                ])
                ->leftJoinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->leftJoinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('coach_schedules.id', '=', 'student_schedules.coach_schedule_id');
                })
                ->WhereNull('student_schedules.coach_schedule_id')
                ->whereNull('coach_schedules.deleted_at')
                ->limit(6)
                ->get();
            foreach ($result as $key => $value) {
                $tools = DB::table('classroom_tools')
                    ->select([
                        'tools.text as tool_name'
                    ])
                    ->where('classroom_id',$value->classroom_id)
                    ->leftJoin('tools','classroom_tools.tool_id','=','tools.id')
                    ->get();

                $value->tools = $tools;
            }
            return response([
                "status"    => 200,
                "data"      => $result,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}
