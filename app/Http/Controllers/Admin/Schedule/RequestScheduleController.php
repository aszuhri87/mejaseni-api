<?php

namespace App\Http\Controllers\Admin\Schedule;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ScheduleRequest;
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

        return view('admin.schedule-request.index', [
            'title' => 'Schedule Request',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
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
                        WHEN coaches.id IS NULL THEN 'Menunggu Admin'
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'Menunggu Coach'
                        WHEN schedule_requests.coach_confirmed = true THEN 'Dikonfirmasi Coach'
                        ELSE 'Ditolak Coach'
                    END status"),
                    DB::raw("CASE
                        WHEN coaches.id IS NULL THEN 'danger'
                        WHEN schedule_requests.coach_confirmed IS NULL THEN 'warning'
                        WHEN schedule_requests.coach_confirmed = true THEN 'success'
                        ELSE 'dark'
                    END status_color")
                ])
                ->leftJoin('classrooms','classrooms.id','=','schedule_requests.classroom_id')
                ->leftJoin('students','students.id','=','schedule_requests.student_id')
                ->leftJoin('coaches','coaches.id','=','schedule_requests.coach_id')
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
            $result = ScheduleRequest::find($id)->update([
                'coach_id' => $request->coach_id,
                'coach_confirmed' => null,
            ]);

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
                    'coach_id' => $schedule_request->coach_id,
                    'type' => 4,
                    'text' => 'Admin memberi jadwal kelas '.$classroom->name.' yang akan dilaksanakan pada '.$schedule_request->datetime,
                    'datetime' => date('Y-m-d H:i:s'),
                ]);

                $notification->schedule_url = url("/coach/schedule-request");

                event(new \App\Events\CoachNotification($notification, $schedule_request->coach_id));
                event(new \App\Events\AdminNotification($notification));

                SocketIO::message($schedule_request->coach_id, 'notification_'.$schedule_request->coach_id, $notification);
                SocketIO::message('admin', 'notification_admin', $notification);
            }

            return response([
                "message"   => 'Successfully saved!'
            ], 200);
        } catch (\Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
}
