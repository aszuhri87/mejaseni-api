<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use DataTables;

class NotificationController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Notification'
            ],
        ];

        return view('student.notification.index', [
            'title' => 'Notification',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }

    public function dt()
    {
        try{

            $data = DB::table('student_notifications')
                ->select([
                    'student_notifications.id',
                    'student_notifications.datetime',
                    'student_notifications.type',
                    'student_notifications.text',
                ])
                ->where('student_id',Auth::guard('student')->user()->id)
                ->whereNull('deleted_at')
                ->orderBy('student_notifications.datetime','DESC')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }
}
