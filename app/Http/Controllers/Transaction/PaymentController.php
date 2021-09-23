<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\DokuController as Doku;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Models\StudentClassroom;
use App\Models\StudentNotification;
use App\Models\Income;

use App\Libraries\SocketIO;

use Auth;
use Redirect;
use Http;

class PaymentController extends Controller
{
    public function waiting($id)
    {
        $transaction = Transaction::find($id);

        if(!$transaction || $transaction->status == 0){
            return redirect('/cart');
        }

        if($transaction->confirmed && $transaction->status == 2){
            return redirect('/payment-success');
        }

        if ($transaction->payment_type == 'va' || $transaction->payment_type == 'alfamart') {
            $response = Http::get($transaction->payment_url);
            $response = json_decode($response->body());
        }else{
            $response = json_decode($transaction->json_transaction);
        }

        return view('cms.transaction.waiting-payment.index', [
            'step' => 2,
            'data' => $response,
            'transaction' => $transaction
        ]);
    }

    public function check_payment($id)
    {
        $transaction = Transaction::find($id);

        if(!$transaction || $transaction->status == 0){
            return response([
                'data' => false
            ]);
        }

        if($transaction->confirmed && $transaction->status == 2){
            return response([
                'data' => true
            ]);
        }

        return response([
            'data' => false
        ]);
    }

    public function success()
    {
        return view('cms.transaction.payment-success.index', [
            'step' => 3,
        ]);
    }

    public function redirect()
    {
        return view('cms.transaction.payment-success.redirect', [
            'step' => 3,
        ]);
    }

    public function notification(Request $request)
    {
        $transaction = Transaction::where([
                'number' => $request['order']['invoice_number'],
                'confirmed' => false,
                'status' => 1
            ])
            ->first();

        DB::transaction(function () use($request, $transaction){
            if($transaction){
                $transaction->json_doku_notification = json_encode($request->all());
                $transaction->update();
            }
        });

        if($transaction && $request['transaction']['status'] == 'SUCCESS'){

            $transaction->confirmed = true;
            $transaction->confirmed_at = date('Y-m-d H:i:s');
            $transaction->status = 2;
            $transaction->total = $request['order']['amount'];

            $data = DB::transaction(function () use($request, $transaction){

                $transaction->update();

                $classrooms = DB::table('transaction_details')
                    ->select([
                        'carts.classroom_id'
                    ])
                    ->leftJoin('carts','carts.id','=','transaction_details.cart_id')
                    ->where('transaction_id', $transaction->id)
                    ->whereNotNull([
                        'carts.classroom_id'
                    ])
                    ->get();

                foreach ($classrooms as $key => $classroom) {
                    StudentClassroom::create([
                        'transaction_id' => $transaction->id,
                        'classroom_id' => $classroom->classroom_id,
                        'student_id' => $transaction->student_id,
                    ]);
                }

                $notification = StudentNotification::create([
                    'student_id' => $transaction->student_id,
                    'transaction_id' => $transaction->id,
                    'type' => 1,
                    'text' => 'Transaksi dengan nomor invoice '.$transaction->number.' telah berhasil.',
                    'datetime' => date('Y-m-d H:i:s')
                ]);

                $carts = DB::table('transaction_details')
                    ->select([
                        'carts.classroom_id',
                        'carts.session_video_id',
                        DB::raw("CASE
                            WHEN carts.classroom_id IS NOT NULL
                                THEN classrooms.price
                            ELSE session_videos.price
                            END price
                        "),
                        DB::raw("CASE
                            WHEN carts.classroom_id IS NOT NULL
                                THEN 1
                            ELSE 2
                            END type_class
                        "),
                        DB::raw("CASE
                            WHEN carts.classroom_id IS NOT NULL
                                THEN null
                            ELSE session_videos.coach_id
                            END session_video_coach_id
                        "),
                    ])
                    ->leftJoin('carts','carts.id','=','transaction_details.cart_id')
                    ->leftJoin('classrooms','classrooms.id','=','carts.classroom_id')
                    ->leftJoin('session_videos','session_videos.id','=','carts.session_video_id')
                    ->where('transaction_id', $transaction->id)
                    ->where(function($query){
                        $query->whereNotNull('carts.classroom_id')
                            ->orWhereNotNull('carts.session_video_id');
                    })
                    ->get();

                foreach ($carts as $key_cart => $cart) {
                    if($cart->type_class != 1){
                        $salary = (int)$cart->price * 0.3;

                        Income::create([
                            'session_video_id' => $cart->session_video_id,
                            'coach_id' => $cart->session_video_coach_id,
                            'amount' => $salary,
                            'formula' => "{$cart->price}*0.3",
                        ]);
                    }
                }

                return $notification;
            });

            event(new \App\Events\StudentNotification($data, $transaction->student_id, "Transaksi Berhasil!"));
            event(new \App\Events\PaymentNotification(true, $transaction->id));
            event(new \App\Events\AdminNotification($data,  "Transaksi Berhasil!"));

            SocketIO::message($transaction->student_id, 'notification_'.$transaction->student_id, $data);
            SocketIO::message($transaction->student_id, 'payment_'.$transaction->id, $data);
            SocketIO::message('admin', 'notification_admin', $data);
        }

        return true;
    }

    public function cancel_payment($id)
    {
        $transaction = Transaction::find($id);

        if(!$transaction){
            return Redirect::back()->withErrors(['message' => 'Pembayaran tidak ditemukan.']);
        }

        if ($transaction->status != 1) {
            return Redirect::back()->withErrors(['message' => 'Permintaan ditolak.']);
        }

        $notification = [
            "number" => $transaction->number
        ];

        DB::transaction(function () use($transaction){
            $transaction->status = 0;
            $transaction->update();
            $transaction->delete();
        });

        if(config('app.env') == 'production') {
            \Mail::send('mail.cancel-payment', compact('notification'), function($message){
                $message->to(Auth::guard('student')->user()->email, Auth::guard('student')->user()->name)
                    ->from('info@mejaseni.com', 'MEJASENI')
                    ->subject("Transaksi Dibatalkan");
            });
        }

        return redirect('cart');
    }
}
