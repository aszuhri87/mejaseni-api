<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Storage;

class TheoryController extends BaseMenu
{
    public function index()
    {
        $navigation = [
            [
                'title' => 'Materi'
            ],
            [
                'title' => 'Data Materi Kelas'
            ],
        ];

        return view('student.theory.index', [
            'title' => 'Theory',
            'navigation' => $navigation,
            'list_menu' => $this->menu_student(),
        ]);
    }

    public function get_theory(Request $request)
    {
        try {
            $path = Storage::disk('s3')->url('/');
            $theory = DB::table('theories')
                ->select([
                    'theories.id',
                    'theories.session_id',
                    'theories.name',
                    'theories.url',
                    'theories.is_premium',
                    'theories.is_video',
                    'theories.description',
                    'theories.upload_date',
                    'theories.price',
                ])
                ->whereNull('deleted_at');

            $session = DB::table('sessions')
                ->select([
                    'sessions.id',
                    'sessions.classroom_id',
                    'theories.id as theory_id',
                    'theories.name',
                    'theories.url',
                    'theories.is_premium',
                    'theories.is_video',
                    'theories.description',
                    'theories.upload_date',
                    'theories.price',
                ])
                ->joinSub($theory, 'theories', function ($join) {
                    $join->on('sessions.id', '=', 'theories.session_id');
                })
                ->whereNull('sessions.deleted_at');

            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name as classroom_name',
                    'sessions.id as session_id',
                    'sessions.theory_id',
                    'sessions.name as theory_name',
                    'sessions.url',
                    'sessions.is_premium',
                    'sessions.is_video',
                    'sessions.description',
                    'sessions.upload_date',
                    'sessions.price',
                ])
                ->joinSub($session, 'sessions', function ($join) {
                    $join->on('classrooms.id', '=', 'sessions.classroom_id');
                })
                ->whereNull('classrooms.deleted_at');

            $coach = DB::table('coaches')
                ->select([
                    'coaches.id',
                    'coaches.name',
                ])
                ->whereNull('coaches.deleted_at');

            $coach_classroom = DB::table('coach_classrooms')
                ->select([
                    'coach_classrooms.id',
                    'coach_classrooms.coach_id',
                    'coaches.name as coach_name'
                ])
                ->joinSub($coach, 'coaches', function ($join) {
                    $join->on('coach_classrooms.coach_id', '=', 'coaches.id');
                })
                ->whereNull('coach_classrooms.deleted_at');

            $coach_schedule = DB::table('coach_schedules')
                ->select([
                    'coach_schedules.id',
                    'coach_schedules.coach_classroom_id',
                    'coach_classrooms.coach_name',
                ])
                ->joinSub($coach_classroom, 'coach_classrooms', function ($join) {
                    $join->on('coach_schedules.coach_classroom_id', '=', 'coach_classrooms.id');
                })
                ->whereNull('coach_schedules.deleted_at');

            $student_schedule = DB::table('student_schedules')
                ->select([
                    'student_schedules.student_classroom_id',
                    'student_schedules.coach_schedule_id',
                    'coach_schedules.coach_name',
                ])
                ->joinSub($coach_schedule, 'coach_schedules', function ($join) {
                    $join->on('student_schedules.coach_schedule_id', '=', 'coach_schedules.id');
                })
                ->whereNull('student_schedules.deleted_at');

            $result = DB::table('student_classrooms')
                ->select([
                    'student_classrooms.id',
                    'student_classrooms.student_id',
                    'classrooms.id as classroom_id',
                    'classrooms.session_id',
                    'classrooms.theory_id',
                    'classrooms.classroom_name',
                    'classrooms.theory_name',
                    'classrooms.url',
                    'classrooms.is_premium',
                    'classrooms.is_video',
                    'classrooms.description',
                    'classrooms.upload_date',
                    'classrooms.price',
                    'student_schedules.coach_name',
                    DB::raw("CONCAT('{$path}',classrooms.url) as file_url"),
                ])
                ->joinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->joinSub($student_schedule, 'student_schedules', function ($join) {
                    $join->on('student_classrooms.id', '=', 'student_schedules.student_classroom_id');
                })
                ->whereNull('student_classrooms.deleted_at')
                ->where('student_classrooms.student_id',Auth::guard('student')->user()->id)
                ->where(function($query) use($request){
                    if(!empty($request->classroom_id)){
                        $query->where('classrooms.id',$request->classroom_id);
                    }

                    if(!empty($request->date_start) && !empty($request->date_end)){
                        $date_start = date('Y-m-d',strtotime($request->date_start));
                        $date_end = date('Y-m-d',strtotime($request->date_end));

                        $query->whereBetween('classrooms.upload_date',[$date_start,$date_end]);
                    }
                })
                ->get();

            foreach ($result as $key => $value) {
                // cek transaction
                $chart = DB::table('carts')
                    ->select([
                        'carts.id',
                        'carts.theory_id'
                    ]);

                $transaction_detail = DB::table('transaction_details')
                    ->select([
                        'transaction_details.transaction_id',
                        'transaction_details.chart_id',
                        'carts.theory_id'
                    ])
                    ->joinSub($chart, 'carts', function ($join) {
                        $join->on('transaction_details.chart_id', '=', 'carts.id');
                    });

                $transaction = DB::table('transactions')
                    ->select([
                        'transactions.id',
                        'transactions.student_id',
                        'transaction_details.theory_id',
                    ])
                    ->joinSub($transaction_detail, 'transaction_details', function ($join) {
                        $join->on('transactions.id', '=', 'transaction_details.transaction_id');
                    })
                    ->where('transactions.student_id',$value->student_id)
                    ->count();
                if($transaction > 0 ){
                    $value->is_buy = true;
                }
                else{
                    $value->is_buy = false;
                }
            }

            return response([
                "status" => 200,
                "data"      => $result,
                "message"   => 'OK!'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function get_class($id)
    {
        try {
            $classroom = DB::table('classrooms')
                ->select([
                    'classrooms.id',
                    'classrooms.name',
                ])
                ->whereNull('classrooms.deleted_at');

            $result = DB::table('student_classrooms')
                ->select([
                    'classrooms.id as classroom_id',
                    'classrooms.name as classroom_name',
                ])
                ->leftJoinSub($classroom, 'classrooms', function ($join) {
                    $join->on('student_classrooms.classroom_id', '=', 'classrooms.id');
                })
                ->where('student_id',$id)
                ->whereNull('student_classrooms.deleted_at')
                ->get();

            return response([
                "status" => 200,
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
}
