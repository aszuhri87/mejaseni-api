<?php

namespace App\Http\Controllers\Admin\Schedule;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\CoachSchedule;

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

        return view('admin.schedule.index', [
            'title' => 'Schedule',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $sub_category_check = DB::table('sub_classroom_categories')
                ->select([
                    'id',
                    'name'
                ])
                ->where('classroom_category_id', $request->classroom_category_id)
                ->whereNull('deleted_at')
                ->count();

            if($sub_category_check > 0 && !isset($request->sub_classroom_category_id)){
                return response([
                    "message"   => 'Sub Kategori harus diisi!'
                ], 400);
            }

            $coach_classroom = DB::table('coach_classrooms')
                ->where([
                    'classroom_id' => $request->classroom_id,
                    'coach_id' => $request->coach_id,
                ])
                ->whereNull('deleted_at')
                ->first();

            if(!$coach_classroom){
                return response([
                    "message"   => 'Kelas dan Coach tidak cocok!'
                ], 400);
            }

            $result = DB::transaction(function () use($request){
                if($request->type_class == 1){
                    $coach_classroom = DB::table('coach_classrooms')
                        ->where([
                            'classroom_id' => $request->classroom_id,
                            'coach_id' => $request->coach_id,
                        ])
                        ->whereNull('deleted_at')
                        ->first();

                    $result = CoachSchedule::create([
                        'coach_classroom_id' => $coach_classroom->id,
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'platform_id' => $request->platform_id,
                        'platform_link' => $request->platform_link,
                        'accepted' => true,
                        'datetime' => date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time)),
                    ]);
                }else{

                }

                return $result;
            });

            return response([
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

    public function update(Request $request, $id)
    {
        try {
            $sub_category_check = DB::table('sub_classroom_categories')
                ->select([
                    'id',
                    'name'
                ])
                ->where('classroom_category_id', $request->classroom_category_id)
                ->whereNull('deleted_at')
                ->count();

            if($sub_category_check > 0 && !isset($request->sub_classroom_category_id)){
                return response([
                    "message"   => 'Sub Kategori harus diisi!'
                ], 400);
            }

            $coach_classroom = DB::table('coach_classrooms')
                ->where([
                    'classroom_id' => $request->classroom_id,
                    'coach_id' => $request->coach_id,
                ])
                ->whereNull('deleted_at')
                ->first();

            if(!$coach_classroom){
                return response([
                    "message"   => 'Kelas dan Coach tidak cocok!'
                ], 400);
            }

            $result = DB::transaction(function () use($request, $id){
                if($request->type_class == 1){
                    $coach_classroom = DB::table('coach_classrooms')
                        ->where([
                            'classroom_id' => $request->classroom_id,
                            'coach_id' => $request->coach_id,
                        ])
                        ->whereNull('deleted_at')
                        ->first();

                    $result = CoachSchedule::find($id)->update([
                        'coach_classroom_id' => $coach_classroom->id,
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'platform_id' => $request->platform_id,
                        'platform_link' => $request->platform_link,
                        'datetime' => date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time)),
                    ]);
                }else{

                }

                return $result;
            });

            return response([
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

    public function all()
    {
        try {
            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'classrooms.name',
                    DB::raw("CASE
                        WHEN datetime::timestamp < now()::timestamp THEN 'secondary'
                        WHEN coach_schedules.accepted = false THEN 'warning'
                        WHEN students.id IS NOT NULL THEN 'primary'
                        ELSE 'success'
                    END color")
                ])
                ->leftJoin('coach_classrooms','coach_schedules.coach_classroom_id','=','coach_classrooms.id')
                ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                ->leftJoin('classrooms','coach_classrooms.classroom_id','=','classrooms.id')
                ->leftJoin('student_schedules','student_schedules.coach_schedule_id','=','coach_schedules.id')
                ->leftJoin('sessions','student_schedules.session_id','=','sessions.id')
                ->leftJoin('student_classrooms','student_classrooms.id','=','student_schedules.student_classroom_id')
                ->leftJoin('students','students.id','=','student_classrooms.student_id')
                ->whereNull('coach_schedules.deleted_at')
                ->whereNull('coach_schedules.deleted_at')
                ->get();

            return response([
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

    public function show($id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'classrooms.name',
                    'classrooms.session_total',
                    'classrooms.session_duration',
                    'students.name as student_name',
                    'coaches.name as coach_name',
                    'sessions.name as session',
                    DB::raw("coach_schedules.datetime::timestamp + INTERVAL '1 MINUTES' * classrooms.session_duration as end_datetime"),
                    DB::raw("CASE WHEN classrooms.package_type = 1 THEN 'Spacial Class' ELSE 'Regular class' END package_type"),
                    DB::raw("CONCAT('{$path}',coaches.image) as image_url"),
                    DB::raw("CASE
                        WHEN datetime::timestamp < now()::timestamp THEN 1
                        WHEN coach_schedules.accepted = false THEN 2
                        WHEN students.id IS NOT NULL THEN 3
                        ELSE 4
                    END status")
                ])
                ->leftJoin('coach_classrooms','coach_schedules.coach_classroom_id','=','coach_classrooms.id')
                ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                ->leftJoin('classrooms','coach_classrooms.classroom_id','=','classrooms.id')
                ->leftJoin('student_schedules','student_schedules.coach_schedule_id','=','coach_schedules.id')
                ->leftJoin('sessions','student_schedules.session_id','=','sessions.id')
                ->leftJoin('student_classrooms','student_classrooms.id','=','student_schedules.student_classroom_id')
                ->leftJoin('students','students.id','=','student_classrooms.student_id')
                ->whereNull('coach_schedules.deleted_at')
                ->where('coach_schedules.id', $id)
                ->first();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function show_edit($id)
    {
        try {
            $result = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'coach_schedules.platform_id',
                    'coach_schedules.platform_link',
                    'classrooms.classroom_category_id',
                    'classrooms.sub_classroom_category_id',
                    'classrooms.id as classroom_id',
                    'coaches.id as coach_id',
                ])
                ->leftJoin('coach_classrooms','coach_schedules.coach_classroom_id','=','coach_classrooms.id')
                ->leftJoin('coaches','coach_classrooms.coach_id','=','coaches.id')
                ->leftJoin('classrooms','coach_classrooms.classroom_id','=','classrooms.id')
                ->where('coach_schedules.id', $id)
                ->first();

            return response([
                "data"      => $result,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function update_time(Request $request, $id)
    {
        try {
            $data = CoachSchedule::find($id)->update([
                'datetime' => date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time)),
            ]);

            return response([
                "data"      => $data,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function confirm($id)
    {
        try {
            $data = CoachSchedule::find($id)->update([
                'admin_id' => Auth::guard('admin')->user()->id,
                'accepted' => true,
            ]);

            return response([
                "data"      => $data,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $data = CoachSchedule::find($id)->delete();

            return response([
                "data"      => $data,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}
