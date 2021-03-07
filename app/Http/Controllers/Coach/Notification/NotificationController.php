<?php

namespace App\Http\Controllers\Coach\Notification;

use App\Http\Controllers\BaseMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Notification'
            ],
        ];

        return view('coach.notification.index', [
            'title' => 'Notification',
            'navigation' => $navigation,
            'list_menu' => $this->menu_coach(),
        ]);
    }

    public function dt()
    {
        $data = DB::table('coach_notifications')
            ->select(
                'coach_notifications.id',
                'coach_notifications.text',
                'coach_notifications.datetime',
                'coach_notifications.type',
                'coaches.name as coach_name',
                'transactions.number',
                'transactions.payment_chanel',
                'transactions.total',
                'classrooms.name as class_name',
                'students.name as student_name',
            )
            ->join('coaches','coaches.id','coach_notifications.coach_id')
            ->leftJoin('transactions','transactions.id','coach_notifications.transaction_id')
            ->leftJoin('coach_schedules','coach_schedules.id','coach_notifications.coach_schedule_id')
            ->leftJoin('student_classrooms','student_classrooms.transaction_id','transactions.id')
            ->leftJoin('students','students.id','student_classrooms.student_id')
            ->leftJoin('classrooms','classrooms.id','student_classrooms.classroom_id')
            ->where('coaches.id',Auth::guard('coach')->id())
            ->orderByDesc('coach_notifications.datetime')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
