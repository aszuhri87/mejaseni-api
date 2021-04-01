<?php

namespace App\Http\Controllers\Admin\ReportTransaction;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\ReviewTransactionStudentExport;

use DataTables;
use Storage;
use PDF;

class TransactionStudentController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Report'
            ],
            [
                'title' => 'Transaction'
            ],
            [
                'title' => 'Student'
            ],
        ];

        return view('admin.report-transaction.student.index', [
            'title' => 'Transaction Student',
            'navigation' => $navigation,
            'list_menu' => $this->menu_admin(),
        ]);
    }

    public function dt(Request $request)
    {
        // dd($request->all());
        $classroom = DB::table('classrooms')
            ->select([
                'classrooms.id',
                'classrooms.name',
                'classrooms.package_type',
            ])
            ->whereNull('classrooms.deleted_at');

        $master_lesson = DB::table('master_lessons')
            ->select([
                'master_lessons.id',
                'master_lessons.name',
            ])
            ->whereNull('master_lessons.deleted_at');

        $session_video = DB::table('session_videos')
            ->select([
                'session_videos.id',
                'session_videos.name',
            ])
            ->whereNull('session_videos.deleted_at');

        $theory = DB::table('theories')
            ->select([
                'theories.id',
                'theories.name',
            ])
            ->whereNull('theories.deleted_at');

        $cart = DB::table('carts')
            ->select([
                'carts.id',
                'classrooms.name as classroom_name',
                'classrooms.package_type',
                'master_lessons.name as master_lessons_name',
                'session_videos.name as session_video_name',
                'theories.name as theory_name',
            ])
            ->leftJoinSub($classroom, 'classrooms', function ($join) {
                $join->on('carts.classroom_id', '=', 'classrooms.id');
            })
            ->leftJoinSub($master_lesson, 'master_lessons', function ($join) {
                $join->on('carts.master_lesson_id', '=', 'master_lessons.id');
            })
            ->leftJoinSub($session_video, 'session_videos', function ($join) {
                $join->on('carts.session_video_id', '=', 'session_videos.id');
            })
            ->leftJoinSub($theory, 'theories', function ($join) {
                $join->on('carts.theory_id', '=', 'theories.id');
            })
            ->whereNull('carts.deleted_at');

        $transaction_detail = DB::table('transaction_details')
            ->select([
                'transaction_details.transaction_id',
                'transaction_details.price',
                'carts.classroom_name',
                'carts.package_type',
                'carts.master_lessons_name',
                'carts.session_video_name',
                'carts.theory_name',
            ])
            ->leftJoinSub($cart, 'carts', function ($join) {
                $join->on('transaction_details.cart_id', '=', 'carts.id');
            })
            ->whereNull('transaction_details.deleted_at');

        $data = DB::table('transactions')
            ->select([
                'transactions.id',
                'transactions.student_id',
                'transactions.datetime',
                'transactions.number',
                'transactions.confirmed',
                'transactions.status',
                'transaction_details.price',
                'transaction_details.classroom_name',
                'transaction_details.package_type',
                'transaction_details.master_lessons_name',
                'transaction_details.session_video_name',
                'transaction_details.theory_name',
            ])
            ->leftJoinSub($transaction_detail, 'transaction_details', function ($join) {
                $join->on('transactions.id', '=', 'transaction_details.transaction_id');
            })
            ->where(function($query) use($request){
                if(!empty($request->student_id)){
                    $query->where('transactions.student_id',$request->student_id);
                }
                if(!empty($request->date_start) && !empty($request->date_end)){
                    $date_start = date('Y-m-d', strtotime($request->date_start));
                    $date_end = date('Y-m-d', strtotime($request->date_end));
                    if($date_start == $date_end){
                        $query->whereDate('transactions.datetime','=',$date_start);
                    }else{
                        $query->whereDate('transactions.datetime','>=',$date_start)
                            ->whereDate('transactions.datetime','<=',$date_end);
                    }
                }
            })
            ->whereNull('transactions.deleted_at')
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function print_pdf(Request $request)
    {
        $classroom = DB::table('classrooms')
            ->select([
                'classrooms.id',
                'classrooms.name',
                'classrooms.package_type',
            ])
            ->whereNull('classrooms.deleted_at');

        $master_lesson = DB::table('master_lessons')
            ->select([
                'master_lessons.id',
                'master_lessons.name',
            ])
            ->whereNull('master_lessons.deleted_at');

        $session_video = DB::table('session_videos')
            ->select([
                'session_videos.id',
                'session_videos.name',
            ])
            ->whereNull('session_videos.deleted_at');

        $theory = DB::table('theories')
            ->select([
                'theories.id',
                'theories.name',
            ])
            ->whereNull('theories.deleted_at');

        $cart = DB::table('carts')
            ->select([
                'carts.id',
                'classrooms.name as classroom_name',
                'classrooms.package_type',
                'master_lessons.name as master_lessons_name',
                'session_videos.name as session_video_name',
                'theories.name as theory_name',
            ])
            ->leftJoinSub($classroom, 'classrooms', function ($join) {
                $join->on('carts.classroom_id', '=', 'classrooms.id');
            })
            ->leftJoinSub($master_lesson, 'master_lessons', function ($join) {
                $join->on('carts.master_lesson_id', '=', 'master_lessons.id');
            })
            ->leftJoinSub($session_video, 'session_videos', function ($join) {
                $join->on('carts.session_video_id', '=', 'session_videos.id');
            })
            ->leftJoinSub($theory, 'theories', function ($join) {
                $join->on('carts.theory_id', '=', 'theories.id');
            })
            ->whereNull('carts.deleted_at');

        $transaction_detail = DB::table('transaction_details')
            ->select([
                'transaction_details.transaction_id',
                'transaction_details.price',
                'carts.classroom_name',
                'carts.package_type',
                'carts.master_lessons_name',
                'carts.session_video_name',
                'carts.theory_name',
            ])
            ->leftJoinSub($cart, 'carts', function ($join) {
                $join->on('transaction_details.cart_id', '=', 'carts.id');
            })
            ->whereNull('transaction_details.deleted_at');

        $data = DB::table('transactions')
            ->select([
                'transactions.id',
                'transactions.student_id',
                'transactions.datetime',
                'transactions.number',
                'transactions.confirmed',
                'transactions.status',
                'transaction_details.price',
                'transaction_details.classroom_name',
                'transaction_details.package_type',
                'transaction_details.master_lessons_name',
                'transaction_details.session_video_name',
                'transaction_details.theory_name',
            ])
            ->leftJoinSub($transaction_detail, 'transaction_details', function ($join) {
                $join->on('transactions.id', '=', 'transaction_details.transaction_id');
            })
            ->where(function($query) use($request){
                if(!empty($request->student_id)){
                    $query->where('transactions.student_id',$request->student_id);
                }
                if(!empty($request->date_start) && !empty($request->date_end)){
                    $date_start = date('Y-m-d', strtotime($request->date_start));
                    $date_end = date('Y-m-d', strtotime($request->date_end));
                    if($date_start == $date_end){
                        $query->whereDate('transactions.datetime','=',$date_start);
                    }else{
                        $query->whereDate('transactions.datetime','>=',$date_start)
                            ->whereDate('transactions.datetime','<=',$date_end);
                    }
                }
            })
            ->whereNull('transactions.deleted_at')
            ->get();

            $student = DB::table('students')
                ->where('id',$request->student_id)
                ->first();

            $pdf = PDF::loadview('admin.print.pdf.transaction-student',compact('data','student'))->setPaper('a4', 'landscape');
            return $pdf->download('transaction-student-'.$student->name.'-'.date('d-m-Y').'.pdf');
    }

    public function print_excel(Request $request)
    {
        $student = DB::table('students')
                ->where('id',$request->student_id)
                ->first();

        return Excel::download(new ReviewTransactionStudentExport($request), 'transaction-student-'.$student->name.'-'.date('d-m-Y').'.xlsx');
    }
}
