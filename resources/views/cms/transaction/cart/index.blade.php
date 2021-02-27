@extends('cms.transaction.layouts.app')

@section('content')
<style>
    .btn-disabled:hover{
        background-color: #6c757d;
    }
</style>

<h2 class="pb-4 text-md-left text-center">Keranjang</h2>
<form action="{{url('cart-store')}}" method="POST">
    @csrf
    <div class="row mt-4">
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
                            <button type="submit" class="btn btn-secondary btn-disabled w-100 row-center btn-payment" disabled>
                                Bayar Sekarang
                                <img class="ml-2" src="assets/img/svg/Sign-in.svg" alt="">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<iframe src="https://sandbox.doku.com/wt-frontend-transaction/dynamic-payment-page?signature=HMACSHA256%3DiyVhX4GyKmhKCpyjvveDX7it7ybmScEhMGrNGQMYffQ%3D&clientId=MCH-0043-1376322085194&invoiceNumber=MJSN202116&requestId=74139" frameborder="1" width="800" height="500"></iframe>
@endsection

@push('additional')
    <div class="w-100 total-payment-mobile px-3 pb-3 pt-1">
        <div class="py-4 mb-2 mt-1 row-center-spacebetween">
            <span>Total Bayar</span>
            <h5 class="grand-total">Rp. -</h5>
        </div>
        <a href="payment.html" class="btn btn-primary w-100 row-center">
            Bayar Sekarang
            <img class="ml-2" src="assets/img/svg/Sign-in.svg" alt="">
        </a>
    </div>
@endpush

@push('script')
@include('cms.transaction.cart.script')
@endpush


