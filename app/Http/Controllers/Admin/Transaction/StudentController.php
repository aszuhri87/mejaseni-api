<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Transaction;

use DataTables;
use Storage;
use Auth;

class StudentController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Transaction'
            ],
            [
                'title' => 'Student'
            ],
        ];

        return view('admin.transaction.student.index', [
            'title' => 'Transaction Student',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt()
    {
        try {
            $data = DB::table('transactions')
                ->select([
                    'transactions.id',
                    'transactions.student_id',
                    'transactions.total',
                    'transactions.number',
                    'transactions.datetime',
                    'transactions.confirmed',
                    'students.name',
                    DB::raw("CASE
                        WHEN transactions.deleted_at IS NOT NULL AND transactions.status = 0
                            THEN 0
                        ELSE transactions.status
                    END as status")
                ])
                ->leftJoin('students', 'students.id','=','transactions.student_id')
                ->get();

            return DataTables::of($data)->addIndexColumn()->make(true);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function detail($id)
    {
        try{
            $data = DB::table('transaction_details')
                ->select([
                    'carts.id',
                    DB::raw("CASE
                        WHEN carts.theory_id IS NOT NULL THEN theories.name
                        WHEN carts.master_lesson_id IS NOT NULL THEN master_lessons.name
                        WHEN carts.session_video_id IS NOT NULL THEN session_videos.name
                        WHEN carts.classroom_id IS NOT NULL THEN classrooms.name
                        WHEN carts.event_id IS NOT NULL THEN events.title
                    END as name"),

                    DB::raw("CASE
                        WHEN carts.theory_id IS NOT NULL THEN theories.price::integer
                        WHEN carts.master_lesson_id IS NOT NULL THEN master_lessons.price::integer
                        WHEN carts.session_video_id IS NOT NULL THEN session_videos.price::integer
                        WHEN carts.classroom_id IS NOT NULL THEN classrooms.price::integer
                        WHEN carts.event_id IS NOT NULL THEN events.total::integer
                    END as price"),

                    DB::raw("CASE
                        WHEN master_lessons.id IS NOT NULL THEN 'Master Lesson'
                        WHEN events.id IS NOT NULL THEN 'Event'
                        ELSE 'Regular Class'
                    END as type"),
                ])
                ->leftJoin('carts','carts.id','transaction_details.cart_id')
                ->leftJoin('theories','theories.id','carts.theory_id')
                ->leftJoin('master_lessons','master_lessons.id','carts.master_lesson_id')
                ->leftJoin('session_videos','session_videos.id','carts.session_video_id')
                ->leftJoin('classrooms','classrooms.id','carts.classroom_id')
                ->leftJoin('events','events.id','carts.event_id')
                ->where('transaction_details.transaction_id', $id)
                ->get();

            return response([
                "status"  => 200,
                "data"    => $data,
                "message" => 'OK!'
            ],200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message" => $e->getMessage(),
            ]);
        }
    }
}
