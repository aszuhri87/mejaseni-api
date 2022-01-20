@extends('cms.transaction.layouts.app')

@section('content')
<div class="border-line"></div>

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
        <button class="btn btn-primary d-flex flex-wrap align-items-center" id="btn-next-zero">
            Lanjutkan Pembayaran
            <img class="ml-2" src="assets/img/svg/Arrow-right.svg" alt="">
        </button>
    </div>
</div>
@endsection

@push('script')
    @include('cms.transaction.payment.script')
@endpush
