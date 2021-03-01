<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\DokuController as Doku;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Transaction;

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

        if ($transaction->payment_type == 'va') {
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

    public function success()
    {
        return view('cms.transaction.payment-success.index', [
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

        if($transaction && $request['transaction']['status'] == 'SUCCESS'){
            if($request['service']['id'] == 'VIRTUAL_ACCOUNT'){
                $transaction->confirmed = true;
                $transaction->confirmed_at = date('Y-m-d H:i:s');
                $transaction->status = 2;
                $transaction->total = $request['order']['amount'];
            }else if($request['service']['id'] == 'CREDIT_CARD'){
                $transaction->confirmed = true;
                $transaction->confirmed_at = date('Y-m-d H:i:s');
                $transaction->status = 2;
                $transaction->total = $request['order']['amount'];
            }

            DB::transaction(function () use($request, $transaction){
                $transaction->json_doku_notification = json_encode($request->all());
                $transaction->update();
            });
        }

        return true;
    }

    public function cancel_payment($id)
    {
        $transaction = Transaction::find($id);

        if(!$transaction){
            return Redirect::back()->withErrors(['message' => 'Pembayaran tidak ditemukan.']);
        }

        $transaction->status = 0;
        $transaction->update();

        return redirect('cart');
    }
}
