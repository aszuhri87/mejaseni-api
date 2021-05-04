<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Redirect;

class DokuController extends Controller
{
    public function generate_payment_code($type, $amount, $invoice, $chanel = null)
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
                    "invoice_number" => $invoice,
                    "amount" => $amount
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
                    "amount" => $amount,
                    "invoice_number" => $invoice,
                    "callback_url" => url('/redirect-blank'),
                    "auto_redirect" => true
                ],
                "customer" => [
                    "id" => Auth::guard('student')->user()->id.'-'.rand(0,20),
                    "name" => trim(Auth::guard('student')->user()->name),
                    "email" => Auth::guard('student')->user()->email,
                    "phone" => Auth::guard('student')->user()->phone,
                    "country" => "ID"
                ],
                "override_configuration" => [
                    "themes" => [
                        "language" => "ID",
                        "background_color" => "FFFFFF",
                        "font_color" => "000000",
                        "button_background_color" => "#9f54b9",
                        "button_font_color" => "fff"
                    ]
                ]
            ];

            $targetPath = '/credit-card/v1/payment-page';
        }else if($type == 'alfamart'){
            $data = [
                "order" => [
                    "amount" => $amount,
                    "invoice_number" => $invoice,
                ],
                "customer" => [
                    "name" => trim(Auth::guard('student')->user()->name),
                    "email" => Auth::guard('student')->user()->email,
                ],
                "online_to_offline_info" => [
                    "expired_time" => 1440,
                    "reusable_status" => false,
                    "info" => "Thank you for shopping"
                ],
                "alfa_info" => [
                    "receipt" => [
                        "footer_message" => "Call Center 021 555-0525"
                    ]
                ]
            ];

            $targetPath = '/alfa-online-to-offline/v2/payment-code';
        }else if($type == 'ovo'){
            $ovo_id = '081522222222';
            $data = [
                "client" =>  [
                    "id" => $client_id
                ],
                "order" =>  [
                    "invoice_number" => $invoice,
                    "amount" =>  $amount
                ],
                "ovo_info" =>  [
                    "ovo_id" =>  $ovo_id
                ],
                "security" => [
                    "check_sum" => hash('sha256', "{$amount}{$client_id}{$invoice}{$ovo_id}{$secret_key}")
                ]
            ];

            return $this->ovo_payment($data);
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

    public function ovo_payment($data)
    {
        date_default_timezone_set('UTC');

        $client_id = config('doku.client_id');
        $secret_key = config('doku.secret_key');
        $expired_time = config('doku.expired_time');
        $base_url = config('doku.is_production') ? 'https://api.doku.com' : 'https://api-sandbox.doku.com';

        $header = array();

        $regId = rand(1, 100000);
        $dateTime = gmdate("Y-m-d H:i:s");
        $dateTime = date(DATE_ISO8601, strtotime($dateTime));
        $dateTimeFinal = substr($dateTime, 0, 19) . "Z";

        $targetPath = '/ovo-emoney/v1/payment';
        $url = $base_url . $targetPath;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
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
