<?php

namespace App\Http\Controllers\Coach\Schedule;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ScheduleRequest;
use App\Models\Session as SessionModel;
use App\Models\CoachSchedule;
use App\Models\StudentSchedule;
use App\Models\Classroom;

use App\Libraries\SocketIO;

use Auth;
use DataTables;

class RequestScheduleController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Schedule Request'
            ],
        ];

        return view('coach.schedule-request.index', [
            'title' => 'Schedule Request',
            'navigation' => $navigation,
            'list_menu' => $this->menu_coach(),
        ]);
    }

    public function dt(Request $request)
    {
        try {
            $data = DB::table('schedule_requests')
                ->select([
                    'schedule_requests.*',
                    'classrooms.name as classroom',
                    'students.name as student',
                    'coaches.name as coach',
                    DB::raw("CASE
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'Menunggu Konfirmasi'
                        WHEN schedule_requests.coach_confirmed = true THEN 'Dikonfirmasi'
                        ELSE 'Ditolak'
                    END status"),
                    DB::raw("CASE
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'warning'
                        WHEN schedule_requests.coach_confirmed = true THEN 'success'
                        ELSE 'dark'
                    END status_color")
                ])
                ->leftJoin('classrooms','classrooms.id','=','schedule_requests.classroom_id')
                ->leftJoin('students','students.id','=','schedule_requests.student_id')
                ->leftJoin('coaches','coaches.id','=','schedule_requests.coach_id')
                ->where('schedule_requests.coach_id', Auth::guard('coach')->user()->id)
                ->whereNull('schedule_requests.deleted_at')
                ->get();

            return DataTables::of($data)->addIndexColumn()->make(true);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $result = DB::transaction(function () use($request, $id){
                $schedule_request = ScheduleRequest::find($id);
                if($schedule_request->coach_confirmed){
                    return false;
                }

                $coach_classroom = DB::table('coach_classrooms')
                    ->where([
                        'classroom_id' => $schedule_request->classroom_id,
                        'coach_id' => Auth::guard('coach')->user()->id,
                    ])
                    ->whereNull('deleted_at')
                    ->first();

                $coach_schedule = CoachSchedule::create([
                    'schedule_request_id' => $id,
                    'coach_classroom_id' => $coach_classroom->id,
                    'accepted' => true,
                    'datetime' => $schedule_request->datetime,
                ]);

                $student_classroom = DB::table('student_classrooms')
                    ->where('classroom_id', $schedule_request->classroom_id)
                    ->where('student_id', $schedule_request->student_id)
                    ->first();

                $session = DB::table('sessions')
                    ->where([
                        'classroom_id' => $schedule_request->classroom_id,
                        'name' => $schedule_request->session
                    ])
                    ->whereNull('deleted_at')
                    ->first();

                if(!$session){
                    $session = SessionModel::create([
                        'name' => $schedule_request->session,
                        'classroom_id' => $schedule_request->classroom_id
                    ]);
                }

                $student_schedule = StudentSchedule::create([
                    'student_classroom_id' => $student_classroom->id,
                    'session_id' => $session->id,
                    'coach_schedule_id' => $coach_schedule->id,
                ]);

                $schedule_request->update([
                    'coach_confirmed' => true,
                ]);

                return true;
            });

            if($result){
                $schedule_request = DB::table('schedule_requests')
                    ->select([
                        'schedule_requests.*',
                        'classrooms.name as classroom',
                        'students.name as student',
                        'coaches.name as coach',
                    ])
                    ->leftJoin('classrooms','classrooms.id','=','schedule_requests.classroom_id')
                    ->leftJoin('students','students.id','=','schedule_requests.student_id')
                    ->leftJoin('coaches','coaches.id','=','schedule_requests.coach_id')
                    ->where('schedule_requests.id', $id)
                    ->first();

                $classroom = Classroom::find($schedule_request->classroom_id);

                $notification = \App\Models\CoachNotification::create([
                    'coach_id' => Auth::guard('coach')->user()->id,
                    'type' => 4,
                    'text' => Auth::guard('coach')->user()->name.' menerima jadwal dari '.$schedule_request->student.', kelas '.$classroom->name.' yang akan dilaksanakan pada '.$schedule_request->datetime,
                    'datetime' => date('Y-m-d H:i:s'),
                ]);

                event(new \App\Events\CoachNotification($notification, Auth::guard('coach')->user()->id));
                event(new \App\Events\AdminNotification($notification));

                SocketIO::message(Auth::guard('coach')->user()->id, 'notification_'.Auth::guard('coach')->user()->id, $notification);
                SocketIO::message('admin', 'notification_admin', $notification);

                $notification = \App\Models\StudentNotification::create([
                    'student_id' => $schedule_request->student_id,
                    'type' => 4,
                    'text' => $schedule_request->coach.' menerima jadwal mu di kelas '.$classroom->name.' yang akan dilaksanakan pada '.$schedule_request->datetime,
                    'datetime' => date('Y-m-d H:i:s')
                ]);

                event(new \App\Events\StudentNotification($notification, $schedule_request->student_id));

                SocketIO::message($schedule_request->student_id, 'notification_'.$schedule_request->student_id, $notification);
            }

            return response([
                'data' => $result,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (\Exception $e) {
            throw new \Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $result = DB::transaction(function () use($id){
                $schedule_request = ScheduleRequest::find($id);

                $schedule_request->update([
                    'coach_confirmed' => false,
                ]);

                return true;
            });

            if($result){
                $schedule_request = DB::table('schedule_requests')
                    ->select([
                        'schedule_requests.*',
                        'classrooms.name as classroom',
                        'students.name as student',
                        'coaches.name as coach',
                    ])
                    ->leftJoin('classrooms','classrooms.id','=','schedule_requests.classroom_id')
                    ->leftJoin('students','students.id','=','schedule_requests.student_id')
                    ->leftJoin('coaches','coaches.id','=','schedule_requests.coach_id')
                    ->where('schedule_requests.id', $id)
                    ->first();

                $classroom = Classroom::find($schedule_request->classroom_id);

                $notification = \App\Models\CoachNotification::create([
                    'coach_id' => Auth::guard('coach')->user()->id,
                    'type' => 4,
                    'text' => Auth::guard('coach')->user()->name.' menolak jadwal dari '.$schedule_request->student.', kelas '.$classroom->name.' yang akan dilaksanakan pada '.$schedule_request->datetime,
                    'datetime' => date('Y-m-d H:i:s'),
                ]);

                event(new \App\Events\CoachNotification($notification, Auth::guard('coach')->user()->id));
                event(new \App\Events\AdminNotification($notification));

                SocketIO::message(Auth::guard('coach')->user()->id, 'notification_'.Auth::guard('coach')->user()->id, $notification);
                SocketIO::message('admin', 'notification_admin', $notification);
            }

            return response([
                'data' => $result,
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
}
