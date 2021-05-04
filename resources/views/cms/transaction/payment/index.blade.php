@extends('cms.transaction.layouts.app')

@section('content')
<style>
#loading {
    display: inline-block;
    width: 17px;
    height: 17px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
    -webkit-animation: spin 1s ease-in-out infinite;
}

.my-spinner-loading {
    padding-top: 10px;
    display: inline-block;
    width: 17px;
    height: 17px;
    border: 3px solid #edc0ff;
    border-radius: 50%;
    border-top-color: #7F16A7;
    animation: spin 1s ease-in-out infinite;
    -webkit-animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { -webkit-transform: rotate(360deg); }
}
@-webkit-keyframes spin {
    to { -webkit-transform: rotate(360deg); }
}
</style>
<div class="border-line"></div>
<div class="row pt-5 pb-4">
    <div class="col-lg-4 text-center text-md-left">
        <h4>Pilih Metode Pembayaran</h4>
    </div>
    <div class="col-lg-8 text-center">
        <div class="payment-option row-center-start">
            <form class="form">
                <div class="plan d-flex flex-row flex-wrap">
                    @if (config('doku.cc_payment'))
                    <input type="radio" class="r-input" name="payment_method" id="credit-card" value="cc" checked>
                    <label class="mr-3" for="credit-card">Kartu Kredit</label>
                    @endif
                    <input type="radio" class="r-input" name="payment_method" id="virtual-account" value="va" @if(!config('doku.cc_payment')) {{'checked'}} @endif>
                    <label class="mr-3" for="virtual-account">Virtual Account</label>

                    <input type="radio" class="r-input" name="payment_method" id="alfamart" value="alfamart">
                    <label class="mr-3" for="alfamart">Alfamart</label>

                    {{-- <input type="radio" class="r-input" name="payment_method" id="ovo" value="ovo">
                    <label class="mr-3" for="ovo">OVO</label> --}}
                </div>
            </form>
        </div>
    </div>
</div>
<div class="border-line"></div>

<div class="list-bank-va" @if(config('doku.cc_payment')) style="display: none;" @endif>
    <div class="row pt-5 pb-4">
        <div class="col-lg-4 text-center text-md-left">
            <h4>Pilih Bank</h4>
        </div>
        <div class="col-lg-8">
            <div class="payment-option row-center-start">
                <form class="form column-center-start">
                    @if (config('doku.bca_payment'))
                    <div class="plan row-center-start my-2">
                        <div class="payment-method-item__img">
                            <img src="assets/img/bca.png" alt="">
                        </div>
                        <input type="radio" name="payment_chanel" id="bca-virtual-account"
                            value="bca-virtual-account">
                        <label class="mr-0 mr-md-3 mt-2 mt-md-0" for="bca-virtual-account">Bank BCA</label>
                    </div>
                    @endif
                    <div class="plan row-center-start my-2">
                        <div class="payment-method-item__img">
                            <img src="assets/img/bank_mandiri.png" alt="">
                        </div>
                        <input type="radio" name="payment_chanel" id="mandiri-virtual-account"
                            value="mandiri-virtual-account">
                        <label for="mandiri-virtual-account">Bank Mandiri</label>
                    </div>
                    <div class="plan row-center-start my-2">
                        <div class="payment-method-item__img">
                            <img src="assets/img/bank_syariah_mandiri.png" alt="">
                        </div>
                        <input type="radio" name="payment_chanel" id="bsm-virtual-account"
                            value="bsm-virtual-account">
                        <label for="bsm-virtual-account">Bank Syariah Indonesia (BSI)</label>
                    </div>
                    <div class="plan row-center-start my-2">
                        <div class="payment-method-item__img">
                            <img src="assets/img/doku.png" alt="">
                        </div>
                        <input type="radio" name="payment_chanel" id="doku-virtual-account"
                            value="doku-virtual-account">
                        <label for="doku-virtual-account">Bank Lainya</label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row row-center py-5">
    <div class="col-6 text-left">
        <h4>Total Bayar</h4>
    </div>
    <div class="col-6 text-right">
        <h4>Rp. {{ number_format($grand_total) }}</h4>
    </div>
</div>
<div class="border-line"></div>

<div class="row pt-5 pb-4">
    <div class="col-12 text-left row-center-spacebetween">
        <a href="{{ url('cart') }}" class="btn btn-white row-center">
            <img class="mr-2" src="assets/img/svg/Arrow-left.svg" alt="">
            Kembali ke Keranjang
        </a>
        <button class="btn btn-primary d-flex flex-wrap align-items-center" id="btn-next">
            Lanjutkan Pembayaran
            <img class="ml-2" src="assets/img/svg/Arrow-right.svg" alt="">
        </button>
    </div>
</div>
@endsection

@push('script')
    @include('cms.transaction.payment.script')
@endpush
