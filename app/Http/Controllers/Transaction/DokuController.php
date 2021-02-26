<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Redirect;

class DokuController extends Controller
{
    public function generate_payment_code($type, $request, $chanel = null)
    {
        date_default_timezone_set('UTC');

        $client_id = config('doku.client_id');
        $secret_key = config('doku.secret_key');
        $expired_time = config('doku.expired_time');
        $base_url = config('doku.is_production') ? 'https://api.doku.com' : 'https://api-sandbox.doku.com';

        $header = array();

        if($type == 'va'){
            $data = array(
                "order" => array(
                    "invoice_number" => rand(0,20),
                    "amount" => 2000
                ),
                "virtual_account_info" => array(
                    "expired_time" => $expired_time,
                    "reusable_status" => false,
                ),
                "customer" => array(
                    "name" => trim(Auth::guard('student')->user()->name),
                    "email" => Auth::guard('student')->user()->email
                ),
            );

            $targetPath = "/{$chanel}/v2/payment-code";
        }else if($type == 'cc'){
            $data = [
                "order" => [
                    "amount" => 90000,
                    "invoice_number" => "INV-".date('YmdHis')."-".rand(0,20),
                    "callback_url" => "https://merchant.com/callback-url",
                    "auto_redirect" => true
                ],
                "customer" => [
                    "id" => Auth::guard('student')->user()->id.'-'.rand(0,20),
                    "name" => trim(Auth::guard('student')->user()->name),
                    "email" => Auth::guard('student')->user()->email,
                    "phone" => Auth::guard('student')->user()->phone,
                    "country" => "ID"
                ]
            ];

            $targetPath = '/credit-card/v1/payment-page';
        }else{
            return null;
        }

        $regId = rand(1, 100000);
        $dateTime = gmdate("Y-m-d H:i:s");
        $dateTime = date(DATE_ISO8601, strtotime($dateTime));
        $dateTimeFinal = substr($dateTime, 0, 19) . "Z";

        $url = $base_url . $targetPath;

        $header['Client-Id'] = $client_id;
        $header['Request-Id'] = $regId;
        $header['Request-Timestamp'] = $dateTimeFinal;
        $header['Request-Target'] = $targetPath;
        $signature = "";

        $signature = $this->generateSignature($header, json_encode($data), $secret_key);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Signature:' . $signature,
            'Request-Id:' . $regId,
            'Client-Id:' . $client_id,
            'Request-Timestamp:' . $dateTimeFinal,
            'Request-Target:' . $targetPath,
        ));

        $responseJson = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if (is_string($responseJson) && $httpcode == 200) {
            return json_decode($responseJson, true);
        } else {
            echo $responseJson;
            return null;
        }
    }

    public function generateSignature($headers, $body, $secret)
    {
        $digest = base64_encode(hash('sha256', $body, true));
        $rawSignature = "Client-Id:" . $headers['Client-Id'] . "\n"
            . "Request-Id:" . $headers['Request-Id'] . "\n"
            . "Request-Timestamp:" . $headers['Request-Timestamp'] . "\n"
            . "Request-Target:" . $headers['Request-Target'] . "\n"
            . "Digest:" . $digest;

        $signature = base64_encode(hash_hmac('sha256', $rawSignature, $secret, true));
        return 'HMACSHA256=' . $signature;
    }
}
