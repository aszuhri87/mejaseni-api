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

            $data_schedule = CoachSchedule::find($id);

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

            if($data_schedule->datetime != date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time))){
                $student_schedules = DB::table('student_schedules')
                    ->select([
                        'student_schedules.id',
                        'student_schedules.coach_schedule_id',
                        'student_classrooms.student_id'
                    ])
                    ->leftJoin('student_classrooms','student_classrooms.id','=','student_schedules.student_classroom_id')
                    ->whereNull('student_schedules.deleted_at');

                $data = CoachSchedule::select([
                        'coach_schedules.*',
                        'coach_classrooms.coach_id',
                        'classrooms.name as classroom',
                        'student_schedules.student_id',
                        'student_schedules.id as student_schedule_id'
                    ])
                    ->leftJoin('coach_classrooms','coach_classrooms.id','=','coach_schedules.coach_classroom_id')
                    ->leftJoin('classrooms','classrooms.id','=','coach_classrooms.classroom_id')
                    ->leftJoinSub($student_schedules, 'student_schedules', function($join){
                        $join->on('student_schedules.coach_schedule_id','coach_schedules.id');
                    })
                    ->where('coach_schedules.id', $id)
                    ->first();

                DB::transaction(function () use($data, $request){
                    $datetime = $data->datetime;

                    $data->update([
                        'datetime' => date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time)),
                    ]);

                    $notification = CoachNotification::create([
                        'coach_id' => $data->coach_id,
                        'coach_schedule_id' => $data->id,
                        'type' => 3,
                        'text' => 'Jadwal kelas '.$data->classroom.' pada '.date('d M Y, H:i:s', strtotime($datetime)).' telah diubah menjadi pada '.date('d M Y, H:i:s', strtotime($data->datetime)).'.',
                        'datetime' => date('Y-m-d H:i:s')
                    ]);

                    if($data->student_id){
                        $notification = StudentNotification::create([
                            'student_id' => $data->student_id,
                            'student_schedule_id' => $data->student_schedule_id,
                            'type' => 3,
                            'text' => 'Jadwal kelas '.$data->classroom.' pada '.date('d M Y, H:i:s', strtotime($datetime)).' telah diubah menjadi pada '.date('d M Y, H:i:s', strtotime($data->datetime)).'.',
                            'datetime' => date('Y-m-d H:i:s')
                        ]);

                        event(new \App\Events\StudentNotification($notification, $data->student_id));
                    }

                    event(new \App\Events\CoachNotification($notification, $data->coach_id));
                    event(new \App\Events\AdminNotification($notification));
                });
            }

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
            $student_schedules = DB::table('student_schedules')
                ->whereNull('deleted_at');

            $coach_classrooms = DB::table('coach_classrooms')
                ->whereNull('deleted_at');

            $coaches = DB::table('coaches')
                ->whereNull('deleted_at');

            $coach_schedules = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.datetime',
                    'classrooms.name',
                    DB::raw("CASE
                        WHEN datetime::timestamp < now()::timestamp THEN 'secondary'
                        WHEN coach_schedules.accepted = false THEN 'warning'
                        WHEN students.id IS NOT NULL THEN 'primary'
                        ELSE 'success'
                    END color"),
                    DB::raw("1 as type")
                ])
                ->joinSub($coach_classrooms, 'coach_classrooms', function($join){
                    $join->on('coach_classrooms.id','coach_schedules.coach_classroom_id');
                })
                ->joinSub($coaches, 'coaches', function($join){
                    $join->on('coaches.id','coach_classrooms.coach_id');
                })
                ->leftJoin('classrooms','coach_classrooms.classroom_id','=','classrooms.id')
                ->leftJoinSub($student_schedules, 'student_schedules', function($join){
                    $join->on('student_schedules.coach_schedule_id','coach_schedules.id');
                })
                ->leftJoin('sessions','student_schedules.session_id','=','sessions.id')
                ->leftJoin('student_classrooms','student_classrooms.id','=','student_schedules.student_classroom_id')
                ->leftJoin('students','students.id','=','student_classrooms.student_id')
                ->whereNull('coach_schedules.deleted_at')
                ->whereNull('coach_schedules.deleted_at')
                ->get()
                ->toArray();

            $master_lessons = DB::table('master_lessons')
                ->select([
                    'master_lessons.id',
                    'master_lessons.datetime',
                    'master_lessons.name',
                    DB::raw("'info' as color"),
                    DB::raw("2 as type")
                ])
                ->whereNull([
                    'master_lessons.deleted_at'
                ])
                ->get()
                ->toArray();

            $result = array_merge($coach_schedules, $master_lessons);

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

    public function show_master_lesson($id)
    {
        try {
            $path = Storage::disk('s3')->url('/');

            $result = DB::table('master_lessons')
                ->select([
                    'master_lessons.name',
                    'master_lessons.slot',
                    'master_lessons.datetime',
                    DB::raw("CONCAT('{$path}',poster) as image_url"),
                    DB::raw("0 as slot_uses"),
                ])
                ->leftJoin('classroom_categories','classroom_categories.id','=','master_lessons.classroom_category_id')
                ->leftJoin('sub_classroom_categories','sub_classroom_categories.id','=','master_lessons.sub_classroom_category_id')
                ->where('master_lessons.id', $id)
                ->first();

            $guest_stars = DB::table('guest_star_master_lessons')
                ->select([
                    'guest_stars.name',
                    DB::raw("CONCAT('{$path}',guest_stars.image) as image_url"),
                ])
                ->leftJoin('guest_stars','guest_star_master_lessons.guest_star_id','=','guest_stars.id')
                ->where('guest_star_master_lessons.master_lesson_id', $id)
                ->get();

            return response([
                "data" => $result,
                "guest_stars" => $guest_stars,
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
            $student_schedules = DB::table('student_schedules')
                ->select([
                    'student_schedules.id',
                    'student_schedules.coach_schedule_id',
                    'student_classrooms.student_id'
                ])
                ->leftJoin('student_classrooms','student_classrooms.id','=','student_schedules.student_classroom_id')
                ->whereNull('student_schedules.deleted_at');

            $data = CoachSchedule::select([
                    'coach_schedules.*',
                    'coach_classrooms.coach_id',
                    'classrooms.name as classroom',
                    'student_schedules.student_id',
                    'student_schedules.id as student_schedule_id'
                ])
                ->leftJoin('coach_classrooms','coach_classrooms.id','=','coach_schedules.coach_classroom_id')
                ->leftJoin('classrooms','classrooms.id','=','coach_classrooms.classroom_id')
                ->leftJoinSub($student_schedules, 'student_schedules', function($join){
                    $join->on('student_schedules.coach_schedule_id','coach_schedules.id');
                })
                ->where('coach_schedules.id', $id)
                ->first();

            DB::transaction(function () use($data, $request){
                $datetime = $data->datetime;

                $data->update([
                    'datetime' => date('Y-m-d H:i:s', strtotime($request->date.' '.$request->time)),
                ]);

                $notification = CoachNotification::create([
                    'coach_id' => $data->coach_id,
                    'coach_schedule_id' => $data->id,
                    'type' => 3,
                    'text' => 'Jadwal kelas '.$data->classroom.' pada '.date('d M Y, H:i:s', strtotime($datetime)).' telah diubah menjadi pada '.date('d M Y, H:i:s', strtotime($data->datetime)).'.',
                    'datetime' => date('Y-m-d H:i:s')
                ]);

                if($data->student_id){
                    $notification = StudentNotification::create([
                        'student_id' => $data->student_id,
                        'student_schedule_id' => $data->student_schedule_id,
                        'type' => 3,
                        'text' => 'Jadwal kelas '.$data->classroom.' pada '.date('d M Y, H:i:s', strtotime($datetime)).' telah diubah menjadi pada '.date('d M Y, H:i:s', strtotime($data->datetime)).'.',
                        'datetime' => date('Y-m-d H:i:s')
                    ]);

                    event(new \App\Events\StudentNotification($notification, $data->student_id));
                }

                event(new \App\Events\CoachNotification($notification, $data->coach_id));
                event(new \App\Events\AdminNotification($notification));
            });

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
            $data = CoachSchedule::select([
                    'coach_schedules.*',
                    'coach_classrooms.coach_id',
                    'classrooms.name as classroom'
                ])
                ->leftJoin('coach_classrooms','coach_classrooms.id','=','coach_schedules.coach_classroom_id')
                ->leftJoin('classrooms','classrooms.id','=','coach_classrooms.classroom_id')
                ->where('coach_schedules.id', $id)
                ->first();

            DB::transaction(function () use($data){
                $data->update([
                    'admin_id' => Auth::guard('admin')->user()->id,
                    'accepted' => true,
                ]);

                $notification = CoachNotification::create([
                    'coach_id' => $data->coach_id,
                    'coach_schedule_id' => $data->id,
                    'type' => 2,
                    'text' => 'Jadwal kelas '.$data->classroom.' pada '.date('d M', strtotime($data->datetime)).' telah disetujui.',
                    'datetime' => date('Y-m-d H:i:s')
                ]);

                event(new \App\Events\CoachNotification($notification, $data->coach_id));
                event(new \App\Events\AdminNotification($notification));
            });

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

    public function update_time_master_lesson(Request $request, $id)
    {
        try {
            $data = MasterLesson::find($id)->update([
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

    public function email_notification($type, $notification, $id = null)
    {
        $user = null;
        if($type == 1){
            $user = DB::table('coaches')
                ->select([
                    'email',
                    'name'
                ])
                ->where('id', $id)
                ->first();
        }else if($type == 2){
            $user = DB::table('students')
                ->select([
                    'name'
                ])
                ->where('id', $id)
                ->first();
        }else{
            $user = Auth::guard('admin')->user();
        }

        try {
            Mail::send('mail.notification', compact('notification'), function($message) use($user){
                $message->to($user->email, $user->name)
                    ->from('info@mejaseni.com', 'MEJASENI')
                    ->subject('Mejaseni Notification');
            });
        } catch (\Exception $th) {
            return false;
        }

        return true;
    }
}
