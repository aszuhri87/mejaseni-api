@extends('cms.layouts.app')

@push('banner')
  @include('cms.layouts.banner')
@endpush


@section('content')
<section>
        <div class="row py-4 my-lg-5 my-0 mt-md-0 column-center">
            <div class="col-lg-8 col-12 mb-4 my-5 column-center">
                <h2 class="text-center">Semua Event</h2>
                <div class="row my-5 w-100">
                    <div class="col-12 col-lg-3 pl-lg-0 pr-lg-3">
                        <button class="btn btn-white w-100 row-center-spacebetween filter-btn">Semua Event <img
                                src="{{ asset('cms/assets/img/svg/chevron-down.svg') }}" alt=""></button>
                        <div class="filter__wrapper p-4">
                            <label>Pilih Kategori Seni</label>
                            <select class="mt-3 mb-4" name="select-subcategories" id="select-subcategories">
                                @foreach($classroom_categories as $classroom_category)
                                    <option>{{$classroom_category->name ? $classroom_category->name:''}}</option>
                                @endforeach
                            </select>
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
                        @foreach($events as $event)
                            <div class="row mb-4 pr-0 pr-lg-5 pb-3">
                                <div class="col-xl-4 mb-3 mb-md-0">
                                    <a href="event-detail.html">
                                        <figure><img src="{{ $event->image_url ? $event->image_url:'' }}" /></figure>
                                    </a>
                                </div>
                                <div class="col-xl-8 px-4">
                                    <div class="badge-left">
                                        <a href="{{ url('event') }}/{{$event->id}}/detail">
                                            <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">{{ $event->title ? $event->title:'' }}</h3>
                                        </a>
                                    </div>
                                    <p class="mt-3 ml-3 desc__store-content">{{ $event->description ? $event->description:'' }}</p>
                                    <div class="detail__store-content ml-3 mt-3">
                                        <div class="coach-name__store-content row-center mr-4">
                                            <img src="{{ asset('cms/assets/img/svg/Crown.svg') }}" class="mr-2" alt="">{{ $event->category ? $event->category:'-'}}
                                        </div>
                                        <div class="class__store-content row-center mt-md-0 mt-3">
                                            <img src="{{ asset('cms/assets/img/svg/calendar.svg') }}" class="mr-2" alt="">{{ $event->date ? $event->date:''}}
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
<div class="shadow filter-btn-mobile" id="filter-btn-mobile">
    <img src="{{ asset('cms/assets/img/svg/filter.svg') }}" alt="">
</div>
@endsection

@push('filter')
    <div class="filter-overlay pt-5 px-5 animate__animated animate__fadeInUp animate__faster">
        <h3 class="mb-5">Filter Data Event</h3>
        <label>Pilih Kategori Seni</label>
        <select class="mt-3 mb-4" name="select-event-categories" id="select-event-categories">
            <option>asdasdasdsa</option>
        </select>
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
        <button type="button" class="close close-btn" aria-label="Close">
          <img src="{{ asset('cms/assets/img/svg/x.svg') }}" alt="">
        </button>
    </div>
@endpush


@push('script')
@include('cms.event-list.script')
@endpush