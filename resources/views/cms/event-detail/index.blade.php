

@extends('cms.layouts.app')

@push('banner')
@include('cms.layouts.banner')
@endpush


@section('content')
<section>
    <div class="row py-0 py-lg-4 my-0 my-lg-5 mt-md-0 column-center">
        <div class="col-12 col-lg-8 mb-0 mb-lg-4 my-0 my-lg-5 column-center event-wrapper">
            <div class="row">
                <div class="col-12 col-lg-8 px-0">
                    <div class="event-image__wrapper">
                        <img class="w-100" src="{{ $event->image_url ? $event->image_url:'' }}" alt="">
                    </div>
                </div>
                <div class="col-12 col-lg-4 px-0">
                    <div class="event-detail__wrapper">
                        <div class="d-flex flex-column justify-content-between p-4 h-100"style="background-color: #ffffff3a; backdrop-filter: blur(50px);">
                            <div>
                                <h4>{{ $event->title ? $event->title:'' }}</h4>
                                <div class="mt-3">
                                    <div class="mb-3">
                                        <p class="label">Lokasi</p>
                                        <p>{{ $event->location ? $event->location:''}}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="label">Tanggal & Waktu</p>
                                        <p>{{ date_format(date_create($event->start_at), "l, d F Y") }} ({{date_format(date_create($event->start_at), "H:i")}}-{{date_format(date_create($event->end_at), "H:i")}})</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="label">Kuota</p>
                                        @if($event->is_full)
                                            <p class="" style="font-weight: 600; color: #F64E60;">Sudah Penuh</p>
                                        @else
                                            <p class="event-quota">Masih Tersedia</p>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <p class="label">Biaya</p>
                                        <p class="event-fee">@convert($event->total)</p>
                                    </div>
                                </div>
                            </div>
                            @if(!$event->is_full)
                                @if($event->is_registered)
                                <a  class="btn btn-primary w-100" data-toggle="modal" data-target="">Anda Sudah Terdaftar</a>
                                @else
                                <a class="btn btn-primary w-100" data-toggle="modal" data-target="@if (Auth::guard('student')->user()){{'#eventRegisterModal'}}@else{{'#loginRequiredModal'}}@endif">Daftar Event</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 px-0">
                    <div class="event-desc__wrapper p-5">
                        <h4>{{ $event->title ? $event->title:'' }}</h4>
                        <p class="mt-3">{{ $event->description ? $event->description:''  }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="eventRegisterModal" tabindex="-1" aria-labelledby="eventRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="p-3">
                    <h3>Daftar Event</h3>
                    <div class="mt-4">
                        <label>Nama Event</label>
                        <h5 class="my-3">{{ $event->title ? $event->title:'0'}}</h5>
                        <label>Harga</label>
                        <h5 class="mt-3 mb-4">{{ $event->total ? $event->total:'0'}}</h5>
                        <div class="border-line"></div>
                        <div class="event-cart">
                            <div class="cart-added pt-4">
                                <div class="success-checkmark">
                                    <div class="check-icon">
                                        <span class="icon-line line-tip"></span>
                                        <span class="icon-line line-long"></span>
                                        <div class="icon-circle"></div>
                                        <div class="icon-fix"></div>
                                    </div>
                                </div>
                                <span class="ml-0 ml-lg-3 mt-3 mt-lg-0 text-center text-lg-left">Telah ditambahkan ke Keranjang</span>
                            </div>
                            <a href="{{ url('student/event') }}/{{ $event->id }}/add-to-cart" class="addtocart btn btn-white mt-4 row-center">
                                <img class="mr-2" src="{{ asset('cms/assets/img/svg/Cart3.svg') }}" alt=""> Keranjang
                            </a>
                            <a href="{{ url('cart') }}" class="btn btn-primary shadow mt-4">Lanjutkan ke Pembayaran</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade pr-0" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="column-center py-3">
                <h3>Kamu belum masuk</h3>
                <a href="{{ url('login') }}" class="btn btn-primary my-4 shadow">Masuk Sekarang</a>
                <span>Belum memiliki akun? <a href="{{ url('login') }}">Daftar Sekarang</a></span>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('script')
@include('cms.event-detail.script')
@endpush