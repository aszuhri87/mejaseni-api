@extends('cms.transaction.layouts.app')

@section('content')
<style>
    .btn-disabled:hover{
        background-color: #6c757d;
    }
</style>

<h2 class="pb-4 text-md-left text-center">Keranjang</h2>
<form action="{{url('cart-store')}}" id="form-payment" method="POST">
    @csrf
    <div class="row my-5 py-3 empty-place">
        <div class="col-xl-12 pr-0 pr-lg-4 column-center">
            <img src="assets/img/svg/empty-cart.svg" alt="">
            <h4 class="mt-3">Wah, keranjang belanjamu kosong</h4>
            <p class="mt-3 mb-4">Yuk, isi dengan kelas impianmu!</p>
            <a href="{{ url('class') }}" class="btn btn-primary shadow">Cari kelas Idaman</a>
        </div>
    </div>
    <div class="row mt-4 cart-place" style="display: none">
        <div class="col-xl-8 pr-4" id="list-place">

        </div>
        <div class="col-xl-4 col-12 px-0 px-md-3">
            <div style="border-radius: 16px; background-image: url('assets/img/svg/footer-bg.svg'); z-index: 0; background-repeat: no-repeat; background-size: cover;">
            <div class="summary-wrapper p-4">
                <div class="column-center-spacebetween">
                    <div class="w-100">
                        <div class="row-center-spacebetween py-3">
                            <span>Total Harga</span>
                            <h5 class="grand-total">Rp. -</h5>
                        </div>
                        <div class="row-center-spacebetween py-3">
                            <span>Diskon</span>
                            <h5>Rp. 0</h5>
                        </div>
                        <div class="border-line mt-4"></div>
                    </div>
                    <div class="w-100 total-payment">
                        <div class="row-center-spacebetween py-4 mb-2 mt-1">
                            <span>Total Bayar</span>
                            <h5 class="grand-total">Rp. -</h5>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 row-center btn-payment">
                            Bayar Sekarang
                            <img class="ml-2" src="assets/img/svg/Sign-in.svg" alt="">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('additional')
    <div class="cart-place" style="display: none">
        <div class="w-100 total-payment-mobile px-3 pb-3 pt-1">
            <div class="py-4 mb-2 mt-1 row-center-spacebetween">
                <span>Total Bayar</span>
                <h5 class="grand-total">Rp. -</h5>
            </div>
            <a href="" class="btn btn-primary w-100 row-center btn-mobile-payment">
                Bayar Sekarang
                <img class="ml-2" src="assets/img/svg/Sign-in.svg" alt="">
            </a>
        </div>
    </div>
@endpush

@push('script')
@include('cms.transaction.cart.script')
@endpush


