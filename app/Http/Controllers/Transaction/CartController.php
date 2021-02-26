<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\DokuController as Doku;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Redirect;
use Http;

class CartController extends Controller
{
    public function index()
    {
        session()->forget('arr_id');

        return view('cms.transaction.cart.index', [
            'step' => 1
        ]);
    }

    public function data()
    {
        try {
            $data = DB::table('carts')
                ->select([
                    'carts.id',
                    DB::raw("CASE
                        WHEN carts.theory_id IS NOT NULL THEN theories.name
                        WHEN carts.master_lesson_id IS NOT NULL THEN master_lessons.name
                        WHEN carts.session_video_id IS NOT NULL THEN session_videos.name
                        WHEN carts.classroom_id IS NOT NULL THEN classrooms.name
                    END as name"),

                    DB::raw("CASE
                        WHEN carts.theory_id IS NOT NULL THEN theories.price
                        WHEN carts.master_lesson_id IS NOT NULL THEN master_lessons.price
                        WHEN carts.session_video_id IS NOT NULL THEN session_videos.price
                        WHEN carts.classroom_id IS NOT NULL THEN classrooms.price
                    END as price"),

                    DB::raw("CASE
                        WHEN master_lessons.id IS NOT NULL THEN 'Master Lesson'
                        ELSE 'Regular Class'
                    END as type"),
                ])
                ->leftJoin('theories','theories.id','carts.theory_id')
                ->leftJoin('master_lessons','master_lessons.id','carts.master_lesson_id')
                ->leftJoin('session_videos','session_videos.id','carts.session_video_id')
                ->leftJoin('classrooms','classrooms.id','carts.classroom_id')
                ->where('carts.student_id', Auth::guard('student')->user()->id)
                ->whereNull([
                    'carts.deleted_at'
                ])
                ->get();

            return response([
                "data"      => $data,
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        session()->put('arr_id', $request->data);

        $data = DB::table('carts')
            ->select([
                'carts.id',
                DB::raw("CASE
                    WHEN carts.theory_id IS NOT NULL THEN theories.price::integer
                    WHEN carts.master_lesson_id IS NOT NULL THEN master_lessons.price::integer
                    WHEN carts.session_video_id IS NOT NULL THEN session_videos.price::integer
                    WHEN carts.classroom_id IS NOT NULL THEN classrooms.price::integer
                END as price"),
            ])
            ->leftJoin('theories','theories.id','carts.theory_id')
            ->leftJoin('master_lessons','master_lessons.id','carts.master_lesson_id')
            ->leftJoin('session_videos','session_videos.id','carts.session_video_id')
            ->leftJoin('classrooms','classrooms.id','carts.classroom_id')
            ->whereIn('carts.id', $request->data)
            ->whereNull([
                'carts.deleted_at'
            ]);

        $result = DB::table('carts')
            ->select([
                DB::raw('SUM(data.price) as grand_total')
            ])
            ->joinSub($data,'data',function($join){
                $join->on('data.id', 'carts.id');
            })
            ->first();

        if(!$result){
            return Redirect::back()->withErrors(['message' => 'Transaksi tidak ditemukan']);
        }

        return view('cms.transaction.payment.index', [
            'grand_total' => $result->grand_total,
            'step' => 2
        ]);
    }

    public function payment(Request $request, Doku $doku)
    {
        try {
            $data = DB::table('carts')
                ->select([
                    'carts.id',
                    DB::raw("CASE
                        WHEN carts.theory_id IS NOT NULL THEN theories.name
                        WHEN carts.master_lesson_id IS NOT NULL THEN master_lessons.name
                        WHEN carts.session_video_id IS NOT NULL THEN session_videos.name
                        WHEN carts.classroom_id IS NOT NULL THEN classrooms.name
                    END as name"),

                    DB::raw("CASE
                        WHEN carts.theory_id IS NOT NULL THEN theories.price::integer
                        WHEN carts.master_lesson_id IS NOT NULL THEN master_lessons.price::integer
                        WHEN carts.session_video_id IS NOT NULL THEN session_videos.price::integer
                        WHEN carts.classroom_id IS NOT NULL THEN classrooms.price::integer
                    END as price"),

                    DB::raw("CASE
                        WHEN master_lessons.id IS NOT NULL THEN 'Master Lesson'
                        ELSE 'Regular Class'
                    END as type"),
                ])
                ->leftJoin('theories','theories.id','carts.theory_id')
                ->leftJoin('master_lessons','master_lessons.id','carts.master_lesson_id')
                ->leftJoin('session_videos','session_videos.id','carts.session_video_id')
                ->leftJoin('classrooms','classrooms.id','carts.classroom_id')
                ->whereIn('carts.id', session()->get('arr_id', null))
                ->whereNull([
                    'carts.deleted_at'
                ])
                ->get();

            $doku = $doku->generate_payment_code($request->type, null, $request->type == 'va' ? $request->va_chanel : null);

            if($doku){
                session()->put('doku', $doku);
            }else{
                return response([
                    "message"   => 'Failed'
                ], 400);
            }

            return response([
                "data"      => $doku,
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
