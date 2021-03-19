@extends('cms.layouts.app')

@push('banner')
  @include('cms.layouts.banner')
@endpush


@section('content')
<section>
        @if(!$events->isEmpty())
            <div class="row py-4">
                <div class="col-12 mb-4 mt-md-0 my-5">
                    <h2 class="text-center">Event Terbaru</h2>
                </div>
                @foreach($events as $event)
                    <div class="col-lg-4 col-12 col-12 p-4">
                        <div class="news-item__wrapper">
                            <img class="h-100" src="{{ isset($event->image_url) ? $event->image_url:''}}" alt="">
                        </div>
                        <div class="news-item-desc__wrapper mt-4 mb-5">
                            <div class="badge-left">
                                <h3 class="ml-3">
                                    <a href="{{ url('event') }}/{{ $event->id}}/detail">
                                        {{ isset($event->title) ? $event->title:''}}
                                </h3>
                                </a>
                            </div>
                            <div class="pl-3 pt-3">
                                <p class="mb-3">{{ isset($event->description) ? $event->description:''}}</p>
                                <a class="link link--arrowed" href="{{ url('event') }}/{{ $event->id}}/detail">Selengkapnya<svg class="arrow-icon ml-1"
                                        xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                        <g fill="none" stroke="#7F16A7" stroke-width="1.5" stroke-linejoin="round"
                                            stroke-miterlimit="10">
                                            <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                                            <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98">
                                            </path>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-12">
                    <div class="more-info__btn">
                        <div class="column-center buttons">
                            <a href="{{ url('event-list') }}" class="btn btn-1">
                                <svg>
                                    <rect x="0" y="0" fill="none" width="100%" height="100%" />
                                </svg>
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(!$news->isEmpty())
            <div class="row py-4 mt-5 mt-md-0">
                <div class="col-12 mb-4 my-5">
                    <h2 class="text-center">Berita Terbaru</h2>
                </div>
                @foreach($news as $new)
                    <div class="col-lg-4 col-12 col-12 p-4">
                        <div class="news-item__wrapper">
                            <img class="h-100" src="{{ isset($new->image_url) ? $new->image_url:''}}" alt="">
                        </div>
                        <div class="news-item-desc__wrapper mt-4 mb-5">
                            <div class="badge-left">
                                <h3 class="ml-3">{{ isset($new->title) ? $new->title:''}}</h3>
                            </div>
                            <div class="pl-3 pt-3">
                                <p class="mb-3">{{ isset($new->description) ? $new->description:''}}</p>
                                <a class="link link--arrowed" href="{{ url('news') }}/{{$new->id}}/detail">Selengkapnya<svg class="arrow-icon ml-1"
                                        xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                        <g fill="none" stroke="#7F16A7" stroke-width="1.5" stroke-linejoin="round"
                                            stroke-miterlimit="10">
                                            <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                                            <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98">
                                            </path>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-12">
                    <div class="more-info__btn">
                        <div class="column-center buttons">
                            <a href="{{ url('news-list') }}" class="btn btn-1">
                                <svg>
                                    <rect x="0" y="0" fill="none" width="100%" height="100%" />
                                </svg>
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($news->isEmpty() && $events->isEmpty())
            <div class="row py-4 mt-5 mt-md-0">
                <div class="col-12 pr-0 pr-lg-4 column-center">
                    <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
                    <h4 class="mt-3 text-center">Wah, News dan Event belum tersedia</h4>
                </div>
            </div>
        @endif
    </section>
@endsection

@push('script')
@include('cms.news-event.script')
@endpush
