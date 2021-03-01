@extends('cms.transaction.layouts.app')

@section('content')
@php
    // dd($data);
@endphp
@if ($transaction->payment_type == 'va')
    <div class="border-line stepper-line"></div>
    <div class="row column-center">
        <div class="col-12 column-center">
            <div class="invoice-number__wrapper d-flex flex-column p-4">
                <p class="text-right mb-2">No Invoice</p>
                <p class="invoice-number text-right">#{{$data->order->invoice_number}}</p>
            </div>
            <lottie-player src="{{asset('cms/assets/img/payment-waiting.json')}}" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
            <p class="mb-3 text-center">Kode pembayaran akan hangus pada</p>
            <h4>{{date('d-m-Y H:i:s', strtotime($data->virtual_account_info->expired_date))}}</h4>
            <input type="text" value="{{$data->virtual_account_info->virtual_account_number}}" id="paymentNumber">
            <div class="payment-code">
                <h2 class="py-3 py-md-4">{{$data->virtual_account_info->virtual_account_number}}</h2>
                <img onclick="copyPaymentNumber()" class="ml-3 duplicate-text copied" width="auto" height="30%" src="assets/img/svg/Duplicate.svg" alt="">
            </div>
            <h5 class="my-5 mt-md-0 text-center">{{$transaction->payment_chanel}}</h5>
            <p class="mb-2">Total Bayar</p>
            <h3 class="mb-5">Rp. {{number_format($data->order->amount)}}</h3>
            <a href="javascript:void(0);" class="btn btn-danger mb-4 row-center shadow">Batalkan Pembayaran</a>
        </div>
        <div class="col-12 col-xl-8 mb-5">
            <h3 class="mt-2 py-3 text-center">Cara Pembayaran</h3>
            <div class="panel-group mt-4" id="accordion" role="tablist" aria-multiselectable="true">
                @foreach ($data->payment_instruction as $index => $item)
                <div class="panel panel-default mt-3">
                    <div class="panel-heading" role="tab" id="heading{{$index}}">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$index}}" aria-expanded="true" aria-controls="collapse{{$index}}">
                                <button class="btn btn-primary w-100 row-center-spacebetween rotate">
                                    {{$item->channel}}
                                    <img src="assets/img/svg/Angle-down 1.svg" alt="">
                                </button>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse{{$index}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading{{$index}}">
                        <div class="panel-body p-4 mt-3">
                            <p>Kami mengumpulkan informasi dengan cara berikut:</p>
                            <ul class="list-group list-group-flush mt-4">
                                @foreach ($item->step as $index => $list)
                                <li class="list-group-item">{{($index + 1).'. '.$list}}</li>
                                @endforeach
                            </ul>
                            <ol>
                            </ol>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@else
<div class="border-line stepper-line"></div>
    <div class="row column-center">
        <div class="col-12 column-center">
            <div class="invoice-number__wrapper d-flex flex-column p-4">
                <p class="text-right mb-2">No Invoice</p>
                <p class="invoice-number text-right">#{{$data->order->invoice_number}}</p>
            </div>
            <lottie-player src="{{asset('cms/assets/img/payment-waiting.json')}}" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
            <h5 class="my-5 mt-md-0 text-center">{{$transaction->payment_chanel}}</h5>
            <p class="mb-2">Total Bayar</p>
            <h3 class="mb-5">Rp. {{number_format($transaction->total)}}</h3>
            <div class="d-flex">
                <a href="{{$transaction->payment_url}}"
                    class="btn btn-primary mb-4 row-center shadow btn-payment">
                    Lanjutkan Pembayaran
                </a>
                <a href="{{url('cancel-payment/'.$transaction->id)}}" class="btn btn-danger mb-4 row-center shadow ml-3">Batalkan Pembayaran</a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('script')
    @include('cms.transaction.waiting-payment.script')
@endpush
