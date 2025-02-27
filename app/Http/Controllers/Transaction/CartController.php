<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\DokuController as Doku;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;

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

            $transaction_details = DB::table('transaction_details')
                ->whereNull('deleted_at');

            $data = DB::table('carts')
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
                        WHEN carts.theory_id IS NOT NULL THEN theories.price
                        WHEN carts.master_lesson_id IS NOT NULL THEN master_lessons.price
                        WHEN carts.session_video_id IS NOT NULL THEN session_videos.price
                        WHEN carts.classroom_id IS NOT NULL THEN classrooms.price
                        WHEN carts.event_id IS NOT NULL THEN events.total
                    END as price"),

                    DB::raw("CASE
                        WHEN master_lessons.id IS NOT NULL THEN 'Master Lesson'
                        WHEN events.id IS NOT NULL THEN 'Event'
                        WHEN classrooms.package_type = 1 THEN 'Special Class'
                        ELSE 'Regular Class'
                    END as type"),
                ])
                ->leftJoin('theories','theories.id','carts.theory_id')
                ->leftJoin('master_lessons','master_lessons.id','carts.master_lesson_id')
                ->leftJoin('session_videos','session_videos.id','carts.session_video_id')
                ->leftJoin('classrooms','classrooms.id','carts.classroom_id')
                ->leftJoin('events','events.id','carts.event_id')
                ->leftJoinSub($transaction_details, 'transaction_details', function($join){
                    $join->on('transaction_details.cart_id','carts.id');
                })
                ->where('carts.student_id', Auth::guard('student')->user()->id)
                ->whereNull('transaction_details.id')
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

        $transaction_details = DB::table('transaction_details')
                ->whereNull('deleted_at');

        $data = DB::table('carts')
            ->select([
                'carts.id',
                DB::raw("CASE
                    WHEN carts.theory_id IS NOT NULL THEN theories.price::integer
                    WHEN carts.master_lesson_id IS NOT NULL THEN master_lessons.price::integer
                    WHEN carts.session_video_id IS NOT NULL THEN session_videos.price::integer
                    WHEN carts.classroom_id IS NOT NULL THEN classrooms.price::integer
                    WHEN carts.event_id IS NOT NULL THEN events.total::integer
                END as price"),
            ])
            ->leftJoin('theories','theories.id','carts.theory_id')
            ->leftJoin('master_lessons','master_lessons.id','carts.master_lesson_id')
            ->leftJoin('session_videos','session_videos.id','carts.session_video_id')
            ->leftJoin('classrooms','classrooms.id','carts.classroom_id')
            ->leftJoin('events','events.id','carts.event_id')
            ->leftJoinSub($transaction_details, 'transaction_details', function($join){
                $join->on('transaction_details.cart_id','carts.id');
            })
            ->where('carts.student_id', Auth::guard('student')->user()->id)
            ->whereIn('carts.id', $request->data)
            ->whereNull([
                'carts.deleted_at',
                'transaction_details.id'
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

        if($result->grand_total == 0){
            return view('cms.transaction.payment.zero-index', [
                'grand_total' => $result->grand_total,
                'step' => 2
            ]);
        }

        return view('cms.transaction.payment.index', [
            'grand_total' => $result->grand_total,
            'step' => 2
        ]);
    }

    public function payment(Request $request, Doku $doku)
    {
        try {
            $carts = DB::table('carts')
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
                        WHEN classrooms.package_type = 1 THEN 'Special Class'
                        ELSE 'Regular Class'
                    END as type"),
                ])
                ->leftJoin('theories','theories.id','carts.theory_id')
                ->leftJoin('master_lessons','master_lessons.id','carts.master_lesson_id')
                ->leftJoin('session_videos','session_videos.id','carts.session_video_id')
                ->leftJoin('classrooms','classrooms.id','carts.classroom_id')
                ->leftJoin('events','events.id','carts.event_id')
                ->whereIn('carts.id', session()->get('arr_id', []))
                ->whereNull([
                    'carts.deleted_at'
                ]);

            $amount = DB::table('carts')
                ->select([
                    DB::raw('SUM(sub_carts.price) as grand_total')
                ])
                ->joinSub($carts,'sub_carts',function($join){
                    $join->on('sub_carts.id', 'carts.id');
                })
                ->first();

            if(!$amount || $amount->grand_total == 0){
                return response([
                    "message"   => 'Amount id zero'
                ], 400);
            }

            $carts =  $carts->get();

            $transaction = DB::transaction(function () use($carts, $request, $amount){
                $tran_number = Transaction::whereYear('created_at', date('Y'))->orderBy(DB::raw("SUBSTRING(number, 9, 4)::INTEGER"),'desc')->withTrashed()->first();

                if($tran_number){
                    $str = explode("MJSN".date('Y'), $tran_number->number);
                    $number = sprintf("%04d", (int)$str[1] + 1);
                    $number = "MJSN".date('Y').$number;
                }else{
                    $number = "MJSN".date('Y').'0001';
                }

                $trans = Transaction::create([
                    'number' => $number,
                    'student_id' => Auth::guard('student')->user()->id,
                    'total' => $amount->grand_total,
                    'status' => 1,
                    'datetime' => date('Y-m-d H:i:s'),
                    'confirmed' => false,
                ]);

                foreach ($carts as $key => $cart) {
                    TransactionDetail::create([
                        'transaction_id' => $trans->id,
                        'cart_id' => $cart->id,
                        'price' => $cart->price,
                    ]);
                }

                session()->forget('arr_id');

                return $trans;
            });

            $doku = $doku->generate_payment_code($request->type, $amount->grand_total, $transaction->number, $request->type == 'va' ? $request->va_chanel : null);

            if($doku){
                if(isset($doku['virtual_account_info'])){
                    $transaction->payment_type = 'va';

                    if($request->type == 'va'){
                        if($request->va_chanel == 'bca-virtual-account'){
                            $transaction->payment_chanel = 'Bank BCA';
                        }else if($request->va_chanel == 'mandiri-virtual-account'){
                            $transaction->payment_chanel = 'Bank Mandiri';
                        }else if($request->va_chanel == 'bsm-virtual-account'){
                            $transaction->payment_chanel = 'Bank Syariah Indonesia (BSI)';
                        }else{
                            $transaction->payment_chanel = 'Bank Lainya';
                        }
                    }

                    $transaction->payment_url = $doku['virtual_account_info']['how_to_pay_api'];
                }else if(isset($doku['online_to_offline_info'])){
                    $transaction->payment_type = 'alfamart';
                    $transaction->payment_chanel = 'Alfamart';
                    $transaction->payment_url = $doku['online_to_offline_info']['how_to_pay_api'];
                }else{
                    $transaction->payment_type = 'cc';
                    $transaction->payment_chanel = 'Credit Card';
                    $transaction->payment_url = $doku['credit_card_payment_page']['url'];
                }

                $transaction->json_transaction = json_encode($doku);

                DB::transaction(function () use($transaction){
                    $transaction->update();
                });

                $transaction = Transaction::find($transaction->id);

                $data_doku = json_decode($transaction->json_transaction);

                if($transaction->payment_type == 'va'){
                    $va_number = $data_doku->virtual_account_info->virtual_account_number;
                }else if($transaction->payment_type == 'alfamart'){
                    $va_number = $data_doku->online_to_offline_info->payment_code;
                }else{
                    $va_number = $data_doku->order->invoice_number;
                }

                $notification = [
                    "number" => $transaction->number,
                    "due_date" => date('Y-m-d H:i:s', strtotime($transaction->date_time. " + 1 DAYS")),
                    "total" => $transaction->total,
                    "payment_chanel" => $transaction->payment_chanel,
                    "va_number" => $va_number,
                ];

                if(config('app.env') == 'production') {
                    \Mail::send('mail.waiting-payment', compact('notification'), function($message){
                        $message->to(Auth::guard('student')->user()->email, Auth::guard('student')->user()->name)
                            ->from('info@mejaseni.com', 'MEJASENI')
                            ->subject("Menunggu Pembayaran");
                    });
                }

                return response([
                    "data"      => $transaction,
                    "redirect_url" => url("waiting-payment/".$transaction->id),
                    "message"   => 'OK'
                ], 200);
            }else{

                DB::transaction(function () use($transaction){
                    $transaction->delete();
                });

                return response([
                    "message"   => 'Failed',
                ], 400);
            }
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function zero_payment(Request $request)
    {
        try {
            $carts = DB::table('carts')
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
                        WHEN classrooms.package_type = 1 THEN 'Special Class'
                        ELSE 'Regular Class'
                    END as type"),
                ])
                ->leftJoin('theories','theories.id','carts.theory_id')
                ->leftJoin('master_lessons','master_lessons.id','carts.master_lesson_id')
                ->leftJoin('session_videos','session_videos.id','carts.session_video_id')
                ->leftJoin('classrooms','classrooms.id','carts.classroom_id')
                ->leftJoin('events','events.id','carts.event_id')
                ->whereIn('carts.id', session()->get('arr_id', []))
                ->whereNull([
                    'carts.deleted_at'
                ]);

            $amount = DB::table('carts')
                ->select([
                    DB::raw('SUM(sub_carts.price) as grand_total')
                ])
                ->joinSub($carts,'sub_carts',function($join){
                    $join->on('sub_carts.id', 'carts.id');
                })
                ->first();

            $carts =  $carts->get();

            $transaction = DB::transaction(function () use($carts, $request, $amount){
                $tran_number = Transaction::orderBy(DB::raw("SUBSTRING(number, 9, 4)::INTEGER"),'desc')->withTrashed()->first();

                if($tran_number){
                    $str = explode("MJSN".date('Y'), $tran_number->number);
                    $number = sprintf("%04d", (int)$str[1] + 1);
                    $number = "MJSN".date('Y').$number;
                }else{
                    $number = "MJSN".date('Y').'0001';
                }

                $trans = Transaction::create([
                    'number' => $number,
                    'student_id' => Auth::guard('student')->user()->id,
                    'total' => $amount->grand_total,
                    'status' => 2,
                    'datetime' => date('Y-m-d H:i:s'),
                    'confirmed' => true,
                    'confirmed_at' => date('Y-m-d H:i:s'),
                ]);

                foreach ($carts as $key => $cart) {
                    TransactionDetail::create([
                        'transaction_id' => $trans->id,
                        'cart_id' => $cart->id,
                        'price' => $cart->price,
                    ]);
                }

                session()->forget('arr_id');

                return $trans;
            });

            return response([
                "data"      => $transaction,
                "redirect_url" => url("waiting-payment/".$transaction->id),
                "message"   => 'OK'
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e);
            return response([
                "message"=> $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {

            $cart = Cart::find($id);

            if(!$cart){
                return response([
                    "message"   => 'Data tidak ditemukan.'
                ], 200);
            }

            DB::transaction(function () use($cart){
                $cart->delete();
            });

            return response([
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
