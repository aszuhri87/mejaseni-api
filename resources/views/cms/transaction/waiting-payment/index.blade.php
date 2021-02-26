@extends('cms.transaction.layouts.app')

@section('content')
<div class="border-line stepper-line"></div>
<div class="row column-center">
    <div class="col-12 column-center">
        <div class="invoice-number__wrapper d-flex flex-column p-4">
            <p class="text-right mb-2">No Invoice</p>
            <p class="invoice-number text-right">#MJSN234678234</p>
        </div>
        <lottie-player src="assets/img/payment-waiting.json" background="transparent" speed="1"
            style="width: 300px; height: 300px;" loop autoplay></lottie-player>
        <p class="mb-3 text-center">Kode pembayaran akan hangus dalam</p>
        <h4>1 Hari 20 Menit 46 Detik</h4>
        <input type="text" value="472398790275927359073" id="paymentNumber">
        <div class="payment-code">
            <h2 class="py-3 py-md-4">4723 9879 0275 9273 5073</h2>
            <img onclick="copyPaymentNumber()" class="ml-3 duplicate-text copied" width="auto" height="30%"
                src="assets/img/svg/Duplicate.svg" alt="">
        </div>
        <h5 class="my-5 mt-md-0 text-center">Bank Mandiri (Di cek otomatis)</h5>
        <p class="mb-2">Total Bayar</p>
        <h3 class="mb-5">Rp. 2.300.000</h3>
        <a href="payment-success.html" class="btn btn-primary mb-4 row-center shadow">Saya Sudah Transfer</a>
    </div>
    <div class="col-12 col-xl-8 mb-5">
        <h3 class="mt-2 py-3 text-center">Cara Pembayaran</h3>
        <div class="panel-group mt-4" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default mt-3">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            <button class="btn btn-primary w-100 row-center-spacebetween rotate">ATM Mandiri
                                <img src="assets/img/svg/Angle-down 1.svg" alt="">
                            </button></a> </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body p-4 mt-3">
                        <p>Kami mengumpulkan informasi dengan cara berikut:</p>
                        <p class="mt-3">Informasi yang anda berikan saat pendaftaran member baik sebagai calon
                            pembeli di halaman store dan peserta kursus. Contohnya, nama lengkap, email, nomor
                            kontak, alamat rumah, alamat pengiriman, nomor rekening bank dll untuk disimpan
                            dalam akun Anda. Layanan kami mengharuskan Anda memberikan informasi dengan benar
                            dan dapat dipertanggungjawabkan secara hukum di Indonesia. Pendaftaran subcribe,
                            formulir download,transaksi dan permintaan kepada sales, billing, abuse, dan
                            technical support. Mungkin Anda menggunakan salah satu jalur komunikasi tersebut
                            yang meminta Anda memberikan data personal Anda yang selanjutnya tercatat dalam
                            sistem kami.</p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-3">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                            href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <button class="btn btn-primary w-100 row-center-spacebetween rotate">Aplikasi Mandiri Online
                                <img src="assets/img/svg/Angle-down 1.svg" alt=""></button></a> </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body p-4 mt-3">
                        <p>Don’t fucking lie to yourself. Be fucking impossible to ignore. Stand so tall that
                            they can’t look past you. Intuition is fucking important. Keep fucking going. It
                            isn’t what you are, but what you’re going to become. You’ve been placed in the
                            crucial moment. Abandon the shelter of insecurity. Respect your fucking craft.
                            Accomplishment validates belief, and belief inspires accomplishment. Practice won’t
                            get you anywhere if you mindlessly fucking practice the same thing. Change only
                            occurs when you work deliberately with purpose toward a goal. You need to sit down
                            and sketch more fucking ideas because stalking your ex on facebook isn’t going to
                            get you anywhere. To surpass others is fucking tough, if you only do as you are told
                            you don’t have it in you to succeed. You’ve been placed in the crucial moment.</p>
                        <p class="mt-3">Abandon the shelter of insecurity. If you’re not being fucking honest
                            with yourself how could you ever hope to communicate something meaningful to someone
                            else? Your rapidograph pens are fucking dried up, the x-acto blades in your bag are
                            rusty, and your mind is dull. Stop clicking your mouse, get messy, go back to the
                            basics and make something fucking original. Fuck. Don’t worry about what other
                            people fucking think. You’ve been placed in the crucial moment. Abandon the shelter
                            of insecurity. What’s important is the fucking drive to see a project through no
                            matter what. Remember it’s called the creative process, it’s not the creative
                            fucking moment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    @include('cms.transaction.payment-waiting.script')
@endpush
