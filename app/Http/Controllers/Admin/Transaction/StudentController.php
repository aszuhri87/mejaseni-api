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
    public function index(Type $var = null)
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
                    'transactions.status',
                    'transactions.confirmed',
                    'students.name'
                ])
                ->leftJoin('students', 'students.id','=','transactions.student_id')
                ->whereNull('transactions.deleted_at')
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
                ])
                ->whereNull('classrooms.deleted_at');

            $session_video = DB::table('session_videos')
                ->select([
                    'session_videos.id',
                    'session_videos.name as session_video_name',
                ])
                ->whereNull('session_videos.deleted_at');

            $master_lesson = DB::table('master_lessons')
                ->select([
                    'master_lessons.id',
                    'master_lessons.name as master_lesson_name',
                ])
                ->whereNull('master_lessons.deleted_at');

            $theory = DB::table('theories')
                ->select([
                    'theories.id',
                    'theories.name as theory_name',
                ])
                ->whereNull('theories.deleted_at');

            $cart = DB::table('carts')
                ->select([
                    'carts.id',
                    'carts.classroom_id',
                    'carts.session_video_id',
                    'carts.master_lesson_id',
                    'carts.theory_id',
                    'classrooms.classroom_name',
                    'session_videos.session_video_name',
                    'master_lessons.master_lesson_name',
                    'theories.theory_name',
                    'classrooms.package_type',
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
                });

            $data = DB::table('transaction_details')
                ->select([
                    'transaction_details.transaction_id',
                    'transaction_details.cart_id',
                    'transaction_details.price',
                    'carts.classroom_id',
                    'carts.session_video_id',
                    'carts.master_lesson_id',
                    'carts.theory_id',
                    'carts.classroom_name',
                    'carts.session_video_name',
                    'carts.master_lesson_name',
                    'carts.theory_name',
                    'carts.package_type',
                ])
                ->leftJoinSub($cart, 'carts', function ($join) {
                    $join->on('transaction_details.cart_id', '=', 'carts.id');
                })
                ->whereNull('transaction_details.deleted_at')
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
