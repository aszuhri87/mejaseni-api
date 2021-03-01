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

            $transaction_detail = DB::table('transaction_details')
                ->select([
                    'transaction_details.transaction_id',
                    'transaction_details.cart_id',
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
                ->whereNull('transaction_details.deleted_at');

            $data = DB::table('transactions')
                ->select([
                    'transactions.id',
                    'transactions.datetime',
                    'transactions.total',
                    'transaction_details.cart_id',
                    'transaction_details.classroom_id',
                    'transaction_details.session_video_id',
                    'transaction_details.master_lesson_id',
                    'transaction_details.theory_id',
                    'transaction_details.classroom_name',
                    'transaction_details.session_video_name',
                    'transaction_details.master_lesson_name',
                    'transaction_details.theory_name',
                    'transaction_details.package_type',
                    DB::raw("(
                        CASE
                            WHEN transaction_details.classroom_id IS NOT NULL THEN
                                transaction_details.classroom_name
                            WHEN transaction_details.session_video_id IS NOT NULL THEN
                                transaction_details.session_video_name
                            WHEN transaction_details.master_lesson_id IS NOT NULL THEN
                                transaction_details.master_lesson_name
                            ELSE
                            transaction_details.theory_name
                        END
                    ) AS class"),
                    DB::raw("(
                        CASE
                            WHEN transaction_details.classroom_id IS NOT NULL THEN
                                CASE
                                    WHEN transaction_details.package_type = 1 THEN
                                        'Special Class'
                                    ELSE
                                        'Regular Class'
                                END
                            WHEN transaction_details.session_video_id IS NOT NULL THEN
                                'Video Class'
                            WHEN transaction_details.master_lesson_id IS NOT NULL THEN
                                'Master Lesson Class'
                            ELSE
                                'Theory'
                        END
                    ) AS type"),
                ])
                ->leftJoinSub($transaction_detail, 'transaction_details', function ($join) {
                    $join->on('transactions.id', '=', 'transaction_details.transaction_id');
                })
                ->where('transactions.student_id',Auth::guard('student')->user()->id)
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
}
