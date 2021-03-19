@extends('cms.layouts.app')

@push('banner')
@include('cms.layouts.banner')
@endpush


@php
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
@endphp


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
                <input class="form-control ml-3" list="datalistOptions" id="search" placeholder="Type to search...">
                <datalist id="datalistOptions">
                </datalist>
            </div>
            <div class="col-md-12">
                <div class="badge-left mt-2">
                    <h2 class="ml-3">{{ isset($video_course->name) ? $video_course->name:'' }}</h2>
                </div>
                <div class="row pt-5">
                    <div class="col-md-8 mb-5 mb-lg-0" id="video-content">
                        @if($video_course_item_open->is_youtube)
                            <div class="content-embed__wrapper">
                                <iframe id="video-course" class="w-100 h-100" src="{{ $video_course_item_open->youtube_url }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                                    
                                </iframe>
                            </div>

                        @else
                            <div class="content-embed__wrapper">
                                <div class="video-quality__wrapper">
                                    <div class="video-quality-selected">
                                        360
                                    </div>
                                    <div class="video-quality-item__wrapper">
                                        @foreach($video_course_item_open_videos as $video_course_item_open_video)
                                            <div class="video-quality__item {{ $video_course_item_open_video->resolution }}" data-url="{{ $video_course_item_open_video->url }}">
                                                {{ $video_course_item_open_video->resolution }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <video id="video-player" class="video-js w-100 h-100 vjs-big-play-centered" controls
                                    preload="auto" data-setup='{}'>
                                    <source id="video-course" src="">
                                    </source>
                                    <p class="vjs-no-js">
                                        To view this video please enable JavaScript, and consider upgrading to a
                                        web browser that
                                        <a href="http://videojs.com/html5-video-support/" target="_blank">
                                            supports HTML5 video
                                        </a>
                                    </p>
                                </video>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="h-100 playlist__wrapper">
                            <div class="playlist-overlay__wrapper p-5">
                                <h3>Daftar Materi</h3>
                                <ul class="my-4 playlist-ul pr-3">
                                    @foreach($video_course_items as $video_course_item)
                                        @if($video_course_item->is_public)
                                            <a href="" data-youtube="{{ $video_course_item->is_youtube }}" data-url="{{ isset($video_course_item->is_youtube) ? $video_course_item->youtube_url:$video_course_item->id}}" class="video-title">
                                                <li class="row-center-spacebetween p-2 my-3 unlocked">
                                                    <div class="row-center-start w-80">
                                                        <div class="circle-border-icon mr-3">
                                                            <img src="{{ asset('cms/assets/img/svg/Play1.svg') }}" alt="">

                                                        </div>
                                                        <span class="title__video-item">{{ isset($video_course_item->name) ? $video_course_item->name:''}}</span>
                                                    </div>
                                                    <span class="mr-2 w-20 text-right">{{ isset($video_course_item->duration) ? gmdate('H:i:s', $video_course_item->duration):'-'}}</span>
                                                </li>
                                            </a>
                                        @else
                                            <a  class="video-title">
                                                <li class="row-center-spacebetween p-2 my-3">
                                                    <div class="row-center-start w-80">
                                                        <div class="circle-border-icon mr-3">
                                                            <img src="{{ asset('cms/assets/img/svg/Lock1.svg') }}" alt="">
                                                        </div>
                                                        <span class="title__video-item">{{ isset($video_course_item->name) ? $video_course_item->name:''}}</span>
                                                    </div>
                                                    <span class="mr-2 w-20 text-right">{{ isset($video_course_item->duration) ? gmdate('H:i:s', $video_course_item->duration):'-'}}</span>
                                                </li>
                                            </a>
                                        @endif

                                    @endforeach
                                    
                                </ul>
                                
                                
                                @if(Auth::guard('student')->check())
                                    @if(!$video_course->is_registered)
                                        <a class="btn btn-primary shadow row-center" data-toggle="modal" data-target="#eventRegisterModal">
                                        <img class="mr-2" src="{{ asset('cms/assets/img/svg/cart-white.svg') }}" alt=""> Beli Paket</a>
                                    @else
                                        <a href="{{ url('student/theory/video-class/video-detail') }}/{{ $video_course->id }}" class="btn btn-primary w-75 row-center">Lihat Kelas <img
                                class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}" alt=""></a>
                                    @endif
                                @else
                                    <a class="btn btn-primary shadow row-center" data-toggle="modal" data-target="#loginRequiredModal">
                                        <img class="mr-2" src="{{ asset('cms/assets/img/svg/cart-white.svg') }}" alt=""> Beli Paket</a>
                                @endif
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
                                <img src="{{ isset($video_course->coach_image_url) ? $video_course->coach_image_url:'' }}" class="w-100 rounded-circle" alt="">
                            </div>
                            <div class="d-flex flex-column">
                                <h3>{{ isset($video_course->coach) ? $video_course->coach:'' }}</h3>
                                <span class="mt-1">{{ isset($video_course->expertise) ? $video_course->expertise:'' }}</span>
                            </div>
                        </div>
                    </div>
                    <ul class="row-center-start class-tab mt-5 mt-md-4">
                        <li id="class-tab-1" class="class-tab active">Deskripsi</li>
                    </ul>
                    <div class="desc__class-tab my-4" id="class-tab-detail-1">
                        <p>
                            {{ $video_course->description }}
                        </p>
                    </div>
                    <div class="discussion__class-tab my-4" id="class-tab-detail-2">
                        <div class="row">
                            <div class="col-12">
                                @foreach($video_course_feedbacks as $video_course_feedback)
                                    <div class="d-flex flex-column flex-lg-row mb-5">
                                        <img class="student-img__discussion" src="{{ isset($video_course_feedback->image_url) ? $video_course_feedback->image_url:''}}" alt="">
                                        <div class="column-start ml-0 ml-lg-4">
                                            <p class="student-name__discussion mt-lg-0 mt-3">{{ isset($video_course_feedback->student) ? $video_course_feedback->student:''}}</p>
                                            <div class="rating__discussion row-center-start mt-2">
                                                <div class="row-center">
                                                    <div class="rating-holder">
                                                        <div class="c-rating c-rating--big" data-rating-value="{{ isset($video_course_feedback->star) ? $video_course_feedback->star:0}}">
                                                            <button>1</button>
                                                            <button>2</button>
                                                            <button>3</button>
                                                            <button>4</button>
                                                            <button>5</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="rating-at__discussion ml-3">{{ isset($video_course_feedback->created_at) ? time_elapsed_string($video_course_feedback->created_at):'-' }}</span>
                                            </div>
                                            <p class="student-review__discussion mt-2">{{ isset($video_course_feedback->description) ? $video_course_feedback->description:''}}</p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
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
                    <h3>Daftar Video Course</h3>
                    <div class="mt-4">
                        <label>Nama Video Course</label>
                        <h5 class="my-3">{{ isset($video_course->name) ? $video_course->name:''}}</h5>
                        <label>Harga</label>
                        <h5 class="mt-3 mb-4">Rp.@convert($video_course->price)</h5>
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
                            <a href="{{ url('student/video-course') }}/{{$video_course->id}}/add-to-cart" class="addtocart btn btn-white mt-4 row-center">
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
                <span>Belum memiliki akun? <a href="{{ url('register') }}">Daftar Sekarang</a></span>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('script')
@include('cms.store-detail.script')
@endpush