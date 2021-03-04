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
        <div class="row py-4 my-lg-5 my-0 mt-md-0 column-center">
            <div class="col-lg-8 col-12 mb-4 my-5 column-center">
                <h2 class="text-center">Semua Berita</h2>
                <div class="row my-5 w-100">
                    <div class="col-12 col-lg-3 pl-lg-0 pr-lg-3">
                        <button class="btn btn-white w-100 row-center-spacebetween filter-btn"
                            id="news_filter_btn">Semua berita <img src="././assets/img/svg/chevron-down.svg"
                                alt=""></button>
                        <div class="filter__wrapper p-4">
                            <label>Pilih Kategori Seni</label>
                            <select class="mt-3 mb-4" name="select-news-categories" id="select-news-categories"></select>
                            <div class="input-group input-daterange d-flex flex-column">
                                <div class="mb-4">
                                    <label for="">Dari Tanggal</label>
                                    <input type="text" class="form-control w-100 mt-3 text-left" value="2012-04-05">
                                </div>
                                <div>
                                    <label for="">Sampai Tanggal</label>
                                    <input type="text" class="form-control w-100 mt-3 text-left" value="2012-04-19">
                                </div>
                            </div>
                            <button class="btn btn-primary mt-4 w-100">Terapkan Filter</button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-9 px-0">
                        <input class="form-control input-rounded" id="exampleDataList" placeholder="Type to search...">
                    </div>
                </div>
                <div class="store-content__wrapper my-4">
                    <div class="shine-hover">
                        @foreach($news as $new)
                            <div class="row mb-4 pr-0 pr-lg-5 pb-3">
                                <div class="col-xl-4 mb-3 mb-md-0">
                                    <a href="news-detail.html">
                                        <figure><img src="{{ asset('cms/assets/img/master-lesson__banner.jpg') }}" /></figure>
                                    </a>
                                </div>
                                <div class="col-xl-8 px-4">
                                    <div class="badge-left">
                                        <a href="{{ url('news') }}/{{$new->id}}/detail">
                                            <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">Loreem ipsum, dolor sit amet consectetur
                                                adipisicing elit. Lorem
                                                ipsum, consequatur.</h3>
                                        </a>
                                    </div>
                                    <p class="mt-3 ml-3 desc__store-content">Lorem ipsum dolor sit amet consectetur
                                        adipisicing elit. Quaerat cum,
                                        laboriosam
                                        saepe libero, eaque laborum natus similique iure totam illo placeat ipsa nostrum
                                        doloremque eligendi dolore aliquam modi temporibus enim sed dicta accusamus?
                                        Deserunt quos accusantium voluptatem distinctio dolor ex excepturi ullam officia,
                                        nobis tempore eveniet, incidunt cum recusandae vitae inventore unde laboriosam
                                        mollitia, illo reprehenderit. Laboriosam quos odio perferendis excepturi voluptas.
                                        Ipsa aspernatur nulla at distinctio facilis vel,!</p>
                                    <div class="detail__store-content ml-3 mt-3">
                                        <div class="class__store-content row-center mt-md-0 mt-3">
                                            <img src="{{ asset('cms/assets/img/svg/calendar.svg') }}" class="mr-2" alt="">Jum, 26 Februari
                                            | 09:00 AM
                                            2021
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="lds-dual-ring mt-4"></div>
            </div>
        </div>
    </section>
    <div class="shadow filter-btn-mobile" id="news-filter-btn-mobile">
        <img src="{{ asset('cms/assets/img/svg/filter.svg') }}" alt="">
    </div>
@endsection

@push('script')
@include('cms.news-list.script')
@endpush