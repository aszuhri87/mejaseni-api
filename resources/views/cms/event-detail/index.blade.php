

@extends('cms.layouts.app')

@push('banner')
@include('cms.layouts.banner')
@endpush

@php
function FunctionName($date)
{
    return date('l', strtotime($date));
}
@endphp

@section('content')
<section>
    <div class="row py-0 py-lg-4 my-0 my-lg-5 mt-md-0 column-center">
        <div class="col-12 col-lg-8 mb-0 mb-lg-4 my-0 my-lg-5 column-center event-wrapper">
            <div class="row">
                <div class="col-12 col-lg-8 px-0">
                    <div class="event-image__wrapper">
                        <img class="w-100" src="{{ asset('cms/assets/img/master-lesson__banner2.jpg') }}" alt="">
                    </div>
                </div>
                <div class="col-12 col-lg-4 px-0">
                    <div class="event-detail__wrapper">
                        <div class="d-flex flex-column justify-content-between p-4 h-100"
                        style="background-color: #ffffff3a; backdrop-filter: blur(50px);">
                        <div>
                            <h4>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos, placeat.
                            </h4>
                            <div class="mt-3">
                                <div class="mb-3">
                                    <p class="label">Lokasi</p>
                                    <p>GMedia Head Office</p>
                                </div>
                                <div class="mb-3">
                                    <p class="label">Tanggal & Waktu</p>
                                    <p>Jum, 26 Februari 2021 (14:00 - 16:00)</p>
                                </div>
                                <div class="mb-3">
                                    <p class="label">Kuota</p>
                                    <p class="event-quota">Masih Tersedia</p>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-primary w-100" data-toggle="modal"
                        data-target="#eventRegisterModal">Daftar Event</a>
                                <!-- <a href="#" class="btn btn-primary w-100" data-toggle="modal"
                                    data-target="#loginRequiredModal">Daftar Event</a> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-12 px-0">
                            <div class="event-desc__wrapper p-5">
                                <h4>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa, qui?</h4>
                                <p class="mt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis veritatis
                                    vitae a
                                    debitis architecto eaque? Debitis quas, enim consequuntur quam nesciunt nam, quae
                                    voluptatum aspernatur corporis iste modi, asperiores dolore laudantium. Reiciendis sequi
                                    quam nulla autem quidem ullam corporis laborum praesentium quibusdam quis repellendus,
                                    labore, veniam nihil animi officia hic voluptate. Cumque, beatae doloremque! Quod
                                    laudantium et similique nisi esse? Iste, provident. A corrupti doloremque expedita,
                                    commodi deleniti facere aliquid cumque placeat exercitationem tempore esse blanditiis
                                    veniam dignissimos voluptates dicta ullam incidunt delectus sit ex facilis laboriosam.
                                    Omnis eum magni temporibus quos suscipit ducimus, quidem cum non officia, maiores dolor.
                                </p>
                                <p class="mt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis veritatis
                                    vitae a
                                    debitis architecto eaque? Debitis quas, enim consequuntur quam nesciunt nam, quae
                                    voluptatum aspernatur corporis iste modi, asperiores dolore laudantium. Reiciendis sequi
                                    quam nulla au facilis laboriosam.
                                    Omnis eum magni temporibus quos suscipit ducimus, quidem cum non officia, maiores dolor.
                                </p>
                                <p class="mt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis veritatis
                                    vitae a
                                    debitis architecto eaque? Debitis quas, enim consequuntur quam nesciunt nam, quae
                                    voluptatum aspernatur corporis iste modi, asperiores dolore laudantium. Reiciendis sequi
                                    quam nullae, beatae doloremque! Quodprovident. A corrupti doloremque expedita,
                                    commodi deleniti facere aliquid cumque placeat exercitationem tempore esse blanditiis
                                    veniam dignissimos voluptates dicta ullam incidunt delectus sit ex facilis laboriosam.
                                    Omnis eum magni temporibus quos suscipit ducimus, quidem cum non officia, maiores dolor.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="eventRegisterModal" tabindex="-1" aria-labelledby="eventRegisterModalLabel"
        aria-hidden="true">
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
                            <h5 class="my-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos, placeat.</h5>
                            <label>Harga</label>
                            <h5 class="mt-3 mb-4">Rp.200.000,00-</h5>
                            <div class="border-line"></div>
                            <div class="row-center-spacebetween">
                                <a href="#" class="btn btn-white mt-4 row-center"><img class="mr-2" src="{{ asset('cms/assets/img/svg/Cart3.svg') }}" alt=""> Keranjang</a>
                                <a href="#" class="btn btn-primary shadow mt-4">Lanjutkan ke Pembayaran</a>
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
                    <a href="#" class="btn btn-primary my-4 shadow">Masuk Sekarang</a>
                    <span>Belum memiliki akun? <a href="#">Daftar Sekarang</a></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@include('cms.event-detail.script')
@endpush