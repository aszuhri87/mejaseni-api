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
                <input class="form-control ml-3" list="datalistOptions" id="search" placeholder="Type to search...">
                <datalist id="datalistOptions">
                </datalist>
            </div>
            <div class="col-md-12">
                <div class="badge-left mt-2">
                    <h2 class="ml-3">{{ $video_course->name ? $video_course->name:'' }}</h2>
                </div>
                <div class="row pt-5">
                    <div class="col-md-8 mb-5 mb-lg-0">
                        <div class="content-embed__wrapper">
                            <div class="video-quality__wrapper">
                                <div class="video-quality-selected">
                                    720p
                                </div>
                                <div class="video-quality-item__wrapper">
                                    <div class="video-quality__item">
                                        1080p
                                    </div>
                                    <div class="video-quality__item">
                                        480p
                                    </div>
                                    <div class="video-quality__item">
                                        360p
                                    </div>
                                </div>
                            </div>
                            <video id="my-player" class="video-js w-100 h-100 vjs-big-play-centered" controls
                                preload="auto" data-setup='{}'>
                                <source src="././assets/tes.m4v" type="video/mp4">
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
                                    <a href="#" class="video-title">
                                        <li class="row-center-spacebetween p-2 my-3">
                                            <div class="row-center-start w-80">
                                                <div class="circle-border-icon mr-3">
                                                    <img src="{{ asset('cms/assets/img/svg/Lock1.svg') }}" alt="">
                                                </div>
                                                <span class="title__video-item">Lorem ipsum dolor sit amet consectetur
                                                    adipisicing elit. Enim, fugit? Totam inventore pariatur perspiciatis
                                                    odit saepe, voluptas facere consequatur veritatis.</span>
                                            </div>
                                            <span class="mr-2 w-20 text-right">1:00</span>
                                        </li>
                                    </a>
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
                            Dengan mengikuti kelas ini, kamu akan mempelajari berbagai macam teknik
                            bermain
                            Piano mulai dari teknik fingering, teori dasar, latihan teknik tangga nada,
                            pengenalan notasi balok, basic chord, arpeggio chord, intervals. Bersama
                            kami,
                            di kelas basic piano ini materinya cocok untuk kamu yang baru akan belajar
                            piano, agar supaya kamu dapat mengetahui cara Lorem ipsum, dolor sit amet
                            consectetur adipisicing elit. Possimus, quaerat repudiandae! Harum, neque.
                            Saepe similique quis unde debitis, provident doloremque? Lorem ipsum dolor
                            sit amet consectetur adipisicing elit. Alias, placeat?
                        </p>
                    </div>
                    <div class="discussion__class-tab my-4" id="class-tab-detail-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-column flex-lg-row mb-5">
                                    <img class="student-img__discussion" src="././assets/img/coach2.png" alt="">
                                    <div class="column-start ml-0 ml-lg-4">
                                        <p class="student-name__discussion mt-lg-0 mt-3">Bintang Yoga Pamungkas</p>
                                        <div class="rating__discussion row-center-start mt-2">
                                            <div class="row-center">
                                                <div class="rating-holder">
                                                    <div class="c-rating c-rating--big" data-rating-value="3.5">
                                                        <button>1</button>
                                                        <button>2</button>
                                                        <button>3</button>
                                                        <button>4</button>
                                                        <button>5</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="rating-at__discussion ml-3">2 Bulan lalu</span>
                                        </div>
                                        <p class="student-review__discussion mt-2">Lorem ipsum dolor sit amet
                                            consectetur adipisicing elit. Libero, adipisci sit laboriosam unde obcaecati
                                            eligendi aut aliquid perspiciatis officia porro. Porro in nisi magni
                                            necessitatibus repudiandae, nobis dolorem pariatur consequatur temporibus
                                            quaerat ipsum, deserunt asperiores ea fugit quisquam, sint doloribus.
                                            Exercitationem, doloremque iure repudiandae dolorem ducimus esse tempora
                                            nesciunt, optio delectus eos at illo, ullam ex placeat voluptatum facere!
                                            Vitae consequatur doloremque explicabo neque repudiandae, laudantium odio
                                            molestiae fugit deserunt quae repellat obcaecati maxime optio harum
                                            cupiditate quod, est nam reiciendis excepturi quis officia modi
                                            exercitationem? Recusandae at fugit tempora ratione, officia ullam
                                            doloremque harum totam minima? Doloremque, ea facilis?</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-lg-row mb-5">
                                    <img class="student-img__discussion" src="././assets/img/coach2.png" alt="">
                                    <div class="column-start ml-0 ml-lg-4">
                                        <p class="student-name__discussion mt-lg-0 mt-3">Bintang Yoga Pamungkas</p>
                                        <div class="rating__discussion row-center-start mt-2">
                                            <div class="row-center">
                                                <div class="rating-holder">
                                                    <div class="c-rating c-rating--big" data-rating-value="3">
                                                        <button>1</button>
                                                        <button>2</button>
                                                        <button>3</button>
                                                        <button>4</button>
                                                        <button>5</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="rating-at__discussion ml-3">2 Bulan lalu</span>
                                        </div>
                                        <p class="student-review__discussion mt-2">Lorem ipsum dolor sit amet
                                            consectetur adipisicing elit. Libero, adipisci sit laboriosam unde obcaecati
                                            eligendi aut aliquid perspiciatis officia porro. Porro in nisi magni
                                            necessitatibus repudiandae, nobis dolorem pariatur consequatur temporibus
                                            quaerat ipsum, deserunt asperiores ea fugit quisquam, sint doloribus.
                                            Exercitationem, doloremque iure repudiandae dolorem ducimus esse tempora
                                            nesciunt, optio delectus eos at illo, ullam ex placeat voluptatum facere!
                                            Vitae consequatur doloremque explicabo neque repudiandae, laudantium odio
                                            molestiae fugit deserunt quae repellat obcaecati maxime optio harum
                                            cupiditate quod, est nam reiciendis excepturi quis officia modi
                                            exercitationem? Recusandae at fugit tempora ratione, officia ullam
                                            doloremque harum totam minima? Doloremque, ea facilis?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('script')
@include('cms.store-detail.script')
@endpush