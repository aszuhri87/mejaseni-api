@extends('cms.layouts.app')

@push('banner')
@include('cms.layouts.banner')
@endpush

@section('content')
<section>
        <div class="row">
            <div class="col-md-12 mt-4 mt-lg-0">
                <div class="mb-5 row-center">
                    <a href="{{ url('store') }}">
                        <div class="rounded-circle back-wrapper row-center shadow">
                            <img src="{{ asset('cms/assets/img/svg/Arrow-left1.svg') }}" alt="">
                    </a>
                </div>
                <input class="form-control ml-3" list="datalistOptions" id="exampleDataList"
                    placeholder="Type to search...">
                <datalist id="datalistOptions">
                    <option value="San Francisco">
                    <option value="New York">
                    <option value="Seattle">
                    <option value="Los Angeles">
                    <option value="Chicago">
                </datalist>
            </div>
            <div class="col-md-12">
                <div class="badge-left mt-2">
                    <h2 class="ml-3">{{ $video_course->name ? $video_course->name:'' }}</h2>
                </div>
                <div class="row pt-5">
                    <div class="col-md-8 mb-5 mb-lg-0">
                        <div class="content-embed__wrapper">
                            <iframe class="w-100 h-100" src="https://www.youtube.com/embed/hXQxSi34GWY" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="h-100 playlist__wrapper">
                            <div class="playlist-overlay__wrapper p-5">
                                <h3>Daftar Materi</h3>
                                <ul class="my-4 playlist-ul pr-3">
                                    @foreach($video_course_items as $video_course_item)
                                        <a href="#" class="video-title">
                                            <li class="row-center-spacebetween p-2 my-3 unlocked">
                                                <div class="row-center-start w-80">
                                                    <div class="circle-border-icon mr-3">
                                                        <img src="{{ asset('cms/assets/img/svg/Play1.svg') }}" alt="">

                                                    </div>
                                                    <span class="title__video-item">{{ $video_course_item->name ? $video_course_item->name:''}}</span>
                                                </div>
                                                <span class="mr-2 w-20 text-right">1:00</span>
                                            </li>
                                        </a>
                                    @endforeach
                                </ul>
                                <a href="#" class="btn btn-primary shadow row-center"><img class="mr-2"
                                        src="{{ asset('cms/assets/img/svg/cart-white.svg') }}" alt=""> Beli Paket</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="video-course__wrapper class-filter__wrapper px-5 pt-5 pb-4 my-5">
                    <div class="coach__class-tab pb-4">
                        <div class="row-center-start">
                            <div class="coach-img__class-tab mr-3">
                                <img src="{{ $video_course->coach_image_url ? $video_course->coach_image_url:'' }}" class="w-100 rounded-circle" alt="">
                            </div>
                            <div class="d-flex flex-column">
                                <h3>{{ $video_course->coach ? $video_course->coach:'' }}</h3>
                                <span class="mt-1">{{ $video_course->expertise ? $video_course->expertise:'' }}</span>
                            </div>
                        </div>
                    </div>
                    <ul class="row-center-start class-tab mt-5 mt-md-4">
                        <li id="class-tab-1" class="class-tab active">Deskripsi</li>
                    </ul>
                    <div class="desc__class-tab my-4" id="class-tab-detail-1">
                        <p>
                            {{ $video_course->description ? $video_course->description:'' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('script')
@include('cms.store-detail.script')
@endpush