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
                        <form>
                            <div class="filter__wrapper p-4">
                                <label>Pilih Kategori Seni</label>
                                <select class="mt-3 mb-4" name="classroom_category" id="select-subcategories">
                                    <option value="0">Semua Kategory</option>
                                    @foreach($classroom_categories as $classroom_category)
                                        <option value="{{$classroom_category->id}}">{{ isset($classroom_category->name) ? $classroom_category->name:''}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group input-daterange d-flex flex-column">
                                    <div class="mb-4">
                                        <label for="">Dari Tanggal</label>
                                        <input type="text" name="start_at" class="form-control w-100 mt-3 text-left" readonly >
                                    </div>
                                    <div>
                                        <label for="">Sampai Tanggal</label>
                                        <input type="text" name="end_at" class="form-control w-100 mt-3 text-left" readonly>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-4 w-100">Terapkan Filter</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-lg-9 px-0">
                        <input class="form-control input-rounded" id="search" placeholder="Type to search...">
                    </div>
                </div>
                <div class="store-content__wrapper my-4">
                    <div class="shine-hover" id="event-list">
                        @foreach($events as $event)
                        <div class="row mb-4 pr-0 pr-lg-5 pb-3">
                            <div class="col-xl-4 mb-3 mb-md-0">
                                <a href="{{ url('event') }}/{{$event->id}}/detail">
                                    <figure><img src="{{ isset($event->image_url) ? $event->image_url:'' }}" /></figure>
                                </a>
                            </div>
                            <div class="col-xl-8 px-4">
                                <div class="badge-left">
                                    <a href="{{ url('event') }}/{{$event->id}}/detail">
                                        <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">{{ isset($event->title) ? $event->title:'' }}</h3>
                                    </a>
                                </div>
                                <p class="mt-3 ml-3 desc__store-content">{{ isset($event->description) ? $event->description:'' }}</p>
                                <div class="detail__store-content ml-3 mt-3">
                                    <div class="coach-name__store-content row-center mr-4">
                                        <img src="{{ asset('cms/assets/img/svg/Crown.svg') }}" class="mr-2" alt="">{{ isset($event->category) ? $event->category:'-'}}
                                    </div>
                                    <div class="class__store-content row-center mt-md-0 mt-3">
                                        <img src="{{ asset('cms/assets/img/svg/calendar.svg') }}" class="mr-2" alt="">{{ $event->date ? date_format(date_create($event->date), "l, d F Y | H:i"):''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div id="loading-scroll">

                </div>
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
        </select>
        <div class="input-group input-daterange d-flex flex-column">
          <div class="mb-4">
            <label for="">Dari Tanggal</label>
            <input type="text" class="form-control w-100 mt-3 text-left" readonly required>
        </div>
        <div>
            <label for="">Sampai Tanggal</label>
            <input type="text" class="form-control w-100 mt-3 text-left" readonly required>
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
