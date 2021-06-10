<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use DataTables;
use Auth;

class InvoiceController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Invoice'
            ],
        ];

        return view('student.invoice.index', [
            'title' => 'Invoice',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }

    public function dt()
    {
        try {
            date_default_timezone_set("Asia/Jakarta");

            $now = date('Y-m-d H:i:s');

            $max_transaction = DB::table('transactions')
                ->select([
                    'transactions.id',
                ])
                ->where('transactions.student_id',Auth::guard('student')->user()->id)
                ->whereRaw("transactions.status = 2")
                ->orderBy('transactions.datetime','desc')
                ->limit(1);

            $data = DB::table('transactions')
                ->select([
                    'transactions.id',
                    'transactions.student_id',
                    'transactions.total',
                    'transactions.number',
                    'transactions.status',
                    'transactions.confirmed',
                    'transactions.datetime',
                    DB::raw("CASE
                        WHEN transactions.datetime::timestamp + INTERVAL '1 DAYS' < '$now'::timestamp
                            AND transactions.status != 2
                            THEN true
                        ELSE false
                    END expired"),
                    DB::raw("CASE
                        WHEN max_transaction.id IS NOT NULL
                            THEN true
                        ELSE false
                    END latest")
                ])
                ->leftJoinSub($max_transaction, 'max_transaction', function ($join){
                    $join->on('transactions.id', '=', 'max_transaction.id');
                })
                ->where('transactions.student_id',Auth::guard('student')->user()->id)
                ->where(function($query){
                    $query->where(function($sub_query){
                        $sub_query->whereNotNull('transactions.deleted_at')
                        ->where('transactions.status',0);
                    })
                    ->orWhereNull('transactions.deleted_at');
                })
                ->orderBy('transactions.datetime', 'desc')
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
            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name as classroom_name',
                    'classrooms.package_type',
                ]);

            $session_video = DB::table('session_videos')
                ->select([
                    'session_videos.id',
                    'session_videos.name as session_video_name',
                ]);

            $master_lesson = DB::table('master_lessons')
                ->select([
                    'master_lessons.id',
                    'master_lessons.name as master_lesson_name',
                ]);

            $theory = DB::table('theories')
                ->select([
                    'theories.id',
                    'theories.name as theory_name',
                ]);

            $event = DB::table('events')
                ->select([
                    'events.id',
                    'events.title as event_name',
                ]);

            $cart = DB::table('carts')
                ->select([
                    'carts.id',
                    'carts.classroom_id',
                    'carts.session_video_id',
                    'carts.master_lesson_id',
                    'carts.theory_id',
                    'carts.event_id',
                    'classrooms.classroom_name',
                    'session_videos.session_video_name',
                    'master_lessons.master_lesson_name',
                    'theories.theory_name',
                    'classrooms.package_type',
                    'events.event_name',
                ])
                ->leftJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('carts.classroom_id', '=', 'classrooms.id');
                })
                ->leftJoinSub($session_video, 'session_videos', function ($join) {
                    $join->on('carts.session_video_id', '=', 'session_videos.id');
                })
                ->leftJoinSub($master_lesson, 'master_lessons', function ($join) {
                    $join->on('carts.master_lesson_id', '=', 'master_lessons.id');
                })
                ->leftJoinSub($theory, 'theories', function ($join) {
                    $join->on('carts.theory_id', '=', 'theories.id');
                })
                ->leftJoinSub($event, 'events', function ($join) {
                    $join->on('carts.event_id', '=', 'events.id');
                })
                ->where('carts.student_id', Auth::guard('student')->user()->id);

            $data = DB::table('transaction_details')
                ->select([
                    'transaction_details.transaction_id',
                    'transaction_details.cart_id',
                    'transaction_details.price',
                    'carts.classroom_id',
                    'carts.session_video_id',
                    'carts.master_lesson_id',
                    'carts.theory_id',
                    'carts.event_id',
                    'carts.classroom_name',
                    'carts.session_video_name',
                    'carts.master_lesson_name',
                    'carts.theory_name',
                    'carts.package_type',
                    'carts.event_name',
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('transaction_details.cart_id', '=', 'carts.id');
                })
                ->where('transaction_details.transaction_id',$id)
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
