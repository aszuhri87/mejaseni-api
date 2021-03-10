@extends('cms.layouts.app')

@push('banner')
  @include('cms.layouts.banner')
@endpush

@section('content')
<section>
        <div class="row py-4 my-lg-5 my-0 mt-md-0 column-center">
            <div class="col-lg-8 col-12 mb-4 my-5 column-center">
                <h2 class="text-center">Semua Berita</h2>
                <div class="row my-5 w-100">
                    <div class="col-12 col-lg-3 pl-lg-0 pr-lg-3">
                        <button class="btn btn-white w-100 row-center-spacebetween filter-btn"
                            id="news_filter_btn">Semua berita <img src="{{ asset('cms/assets/img/svg/chevron-down.svg') }}"
                                alt=""></button>
                        <div class="filter__wrapper p-4">
                            <label>Pilih Kategori Seni</label>
                            <form>
                                <select class="mt-3 mb-4" name="classroom_category" id="select-news-categories"></select>
                                <div class="input-group input-daterange d-flex flex-column">
                                    <div class="mb-4">
                                        <label for="">Dari Tanggal</label>
                                        <input type="text" name="start_at" class="form-control w-100 mt-3 text-left" required>
                                    </div>
                                    <div>
                                        <label for="">Sampai Tanggal</label>
                                        <input type="text" name="end_at" class="form-control w-100 mt-3 text-left" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-4 w-100">Terapkan Filter</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-12 col-lg-9 px-0">
                        <input class="form-control input-rounded" id="search" placeholder="Type to search...">
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
                                            <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">{{ $new->title ? $new->title:''}}</h3>
                                        </a>
                                    </div>
                                    <p class="mt-3 ml-3 desc__store-content">{{ $new->description ? $new->description:''}}</p>
                                    <div class="detail__store-content ml-3 mt-3">
                                        <div class="class__store-content row-center mt-md-0 mt-3">
                                            <img src="{{ asset('cms/assets/img/svg/calendar.svg') }}" class="mr-2" alt="">{{ $new->created_at ? date_format(date_create($new->created_at), "l, d F Y | H:i"):''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                {{-- <div class="lds-dual-ring mt-4"></div> --}}
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