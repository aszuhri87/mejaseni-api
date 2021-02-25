<div class="checkout-stepper row-start-center pt-3 pb-3 mb-5">
    <div class="mx-4 column-center {{$step > 0 ? 'active' : ''}}">
        <div class="stepper-badge mr-2 mb-2">
            @if ($step > 1)
            <img src="assets/img/svg/Check.svg" alt="">
            @else
            1
            @endif
        </div>
        <h5>Keranjang</h5>
    </div>
    <div class="border-line stepper-border mt-4 {{$step > 1 ? 'stepper-border-active' : ''}}"></div>
    <div class="mx-4 column-center {{$step > 1 ? 'active' : ''}}">
        <div class="stepper-badge mb-2">
            @if ($step > 2)
            <img src="assets/img/svg/Check.svg" alt="">
            @else
            2
            @endif
        </div>
        <h5>Pembayaran</h5>
    </div>
    <div class="border-line stepper-border mt-4 {{$step > 2 ? 'stepper-border-active' : ''}}"></div>
    <div class="mx-4 column-center text-center {{$step > 2 ? 'active' : ''}}">
        <div class="stepper-badge mb-2">
            @if ($step > 3)
            <img src="assets/img/svg/Check.svg" alt="">
            @else
            3
            @endif
        </div>
        <h5>Pemesanan Berhasil</h5>
    </div>
</div>
