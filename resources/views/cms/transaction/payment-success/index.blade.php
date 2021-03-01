@extends('cms.transaction.layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-md-12 column-center text-center">
        <lottie-player src="cms/assets/img/payment-success.json" background="transparent" speed="1"
            style="width: 300px; height: 300px;" loop autoplay></lottie-player>
        <h1>Pembelian Berhasil!</h1>
        <p class="my-4">Pemesanan Anda berhasil. Silahkan halaman "Kelas Saya" untuk mulai belajar.
        </p>
        <a href="#" class="btn btn-primary mt-2">Lihat Kelas Saya</a>
        <a href="#" class="mt-4 mb-5" alt=""> Lihat Kelas Lain</a>
    </div>
</div>
@endsection

@push('script')
    @include('cms.transaction.payment-success.script')
@endpush
