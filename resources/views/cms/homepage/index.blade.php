@extends('cms.layouts.app')

@push('banner')
@include('cms.layouts.banner')
@endpush

@section('content')
<style>
    @media only screen and (max-width: 1500px) {
        .lg-xl {
           display: none;
           padding: 0;
        }

        .lg-sm {
           display: block;
        }
    }
</style>
<section>
    <div class="row mx-0">

        @if(!Auth::guard('student')->check())
        <div class="col-xl-4 col-md-12 col-sm-12 login-aside">
            <div class="login-wrapper p-5">
                <h1>Welcome to mejaseni {{ Auth::guard('student')->user()}}</h1>
                <div class="pt-3">
                    <a href="{{url('auth/facebook')}}" class="btn btn-blue row-center mt-5"><img
                            class="img__btn-login mr-2" src="{{ asset('cms/assets/img/logo-facebook.svg') }}"
                            alt="">Lanjutkan dengan Facebook
                    </a>
                    <a href="{{url('auth/google')}}" class="btn btn-white row-center mt-3"><img
                            class="img__btn-login mr-2" src="{{ asset('cms/assets/img/logo-google.svg') }}"
                            alt="">Lanjutkan dengan Google
                    </a>
                    <div class="row-center pt-5">
                        <span class="border-line"></span>
                        <span class="mx-3">Atau</span>
                        <span class="border-line"></span>
                    </div>
                    <div class="row-spacearound mt-5">
                        <a href="{{ url('register') }}" class="btn btn-primary w-75 row-center">Daftar Sekarang
                            <img class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}" alt="">
                        </a>
                        <a href="{{ url('login') }}" class="btn btn-white w-25 ml-3">Login</a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="col-xl-4 col-md-12 col-sm-12 login-aside" data-aos="zoom-out">
            <div class="login-wrapper p-5 p-lg-4 p-xl-5">
                <img class="rounded-circle img-logged mb-3" src="{{ Auth::guard('student')->user()->getImageUrl() }}"
                    alt="">
                <h1>Halo, <br />{{ Auth::guard('student')->user()->name }}!!!</h1>
                <div class="pt-3">
                    <div class="row-spacearound mt-3">
                        <a href="{{ url('student/my-class') }}" class="btn btn-primary w-75 row-center">Lihat Kelas <img
                                class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}" alt=""></a>
                        <a href="{{ url('logout') }}"
                            class="btn btn-white w-25 mt-0 mt-xl-0 mt-lg-3 ml-3 ml-lg-0 ml-xl-3">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-lg-8 col-md-12 col-sm-12 px-0 mt-0 mt-md-5 mt-lg-0">
            @if(!$events->isEmpty())
            <div class="splide pb-md-5 pb-3 mb-5 w-100" id="splide1">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($events as $event)
                        <li class="splide__slide pb-md-0 pb-5">
                            <div class="content-embed__wrapper">
                                <img src="{{ $event->image_url ? $event->image_url:''}}"
                                    data-splide-lazy="path-to-the-image" alt="">
                                <div class="px-3 px-md-0 pt-3 pt-md-0 pb-1  ">
                                    <div class="badge-left">
                                        <h3 class="mt-3 ml-2">{{ $event->title ? $event->title:''}}</h3>
                                    </div>
                                    <p class="my-3 desc__slider-content">
                                        {{ $event->description ? $event->description:''}}</p>
                                    <a class="link link--arrowed"
                                        href="{{ url('event') }}/{{$event->id}}/detail">Selengkapnya
                                        <svg class="arrow-icon ml-1" xmlns="http://www.w3.org/2000/svg" width="32"
                                            height="32" viewBox="0 0 32 32">
                                            <g fill="none" stroke="#7F16A7" stroke-width="1.5" stroke-linejoin="round"
                                                stroke-miterlimit="10">
                                                <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                                                <path class="arrow-icon--arrow"
                                                    d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @else
            <div class="col-12 pr-0 pr-lg-4 mt-4 column-center">
                <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
                <h4 class="mt-3 text-center">Wah, Event belum tersedia</h4>
            </div>
            @endif

        </div>
    </div>
</section>

@if(!$classroom_categories->isEmpty())
<style>
    @media only screen and (max-width: 770px) {
        .splide__track{
            height: 320px;
        }
    }

    @media only screen and (max-width: 1199px) {
        .splide__track{
            height: 250px;
        }
    }

    @media only screen and (max-width: 1570px) {
        .splide__track{
            height: 420px;
        }
    }
</style>
<section id="class-category" class="pb-5">
    <h1 class="color-white mt-3 mb-5 pt-md-0 pt-5 text-center">Temukan Minatmu</h1>
    <div class="row mx-0">
        <div class="col-md-12">
            <div class="splide w-100" id="splide2">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($classroom_categories as $classroom_category)
                        <li class="splide__slide px-2 px-md-4 pb-5">
                            <div class="content-embed__wrapper class-category__splide">
                                <img src="{{ $classroom_category->image_url ? $classroom_category->image_url:''}}"
                                    alt="">
                            </div>
                            <div class="px-4">
                                <div class="badge-left">
                                    <h3 class="mt-3 mt-md-5 ml-2">
                                        {{ $classroom_category->name ? $classroom_category->name:''}}</h3>
                                </div>
                                <p class="my-3 desc__class-category">
                                    {{ $classroom_category->description ? $classroom_category->description:''}}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<section id="program-package">
    <div class="row mx-0 my-5 pb-5 pb-md-0 pb-lg-3 pb-xl-5">
        <div class="col-md-12 text-center my-5 pt-4 pt-md-0 pb-2 pb-md-5">
            <h1>Program Kami</h1>
        </div>
        @foreach($programs as $program)
        <div class="col-xl-3 col-lg-6 col-12 mb-md-5 px-4 px-md-3 py-3 py-md-0">
            <div class="our-program__item p-5" data-aos="zoom-out" data-aos-delay="0">
                <img class="img__program-thumbnail" src="{{ $program->image_url ? $program->image_url:'' }}" alt="">
                <div class="badge-left">
                    <h3 class="mt-3 ml-2">{{ $program->name ? $program->name:'' }}</h3>
                </div>
                <p class="my-3 pt-1">{{ $program->description ? $program->description:'' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

<section id="professional-coach">
    <div class="row mx-0 d-flex">
        <div class="col-xl-4 col-12 order-md-1 order-2 my-5 pt-4 pt-md-0 pb-2 pb-md-5">
            <h1>Kembangkan Bakatmu</h1>
            <p class="pt-4 px-md-0 px-3 desc__professional-coach">Lorem ipsum dolor sit amet, consectetur adipisicing
                elit.
                Ea nobis nostrum sit rem! Inventore
                quia distinctio fugiat rerum dolor cumque.</p>
            <div class="pt-2 pb-4 mini-coach-img__wrapper d-flex flex-row flex-wrap text-center">
                @foreach($coachs as $coach)
                <img src="{{ $coach->image_url ? $coach->image_url :'/assets/cms/assets/img/coach.png' }}"
                    class="mini-coach-img__item" alt="">
                @endforeach
            </div>
            <h5>dan banyak lagi lainnya..</h5>
        </div>
        <div class="col-xl-8 order-md-2 order-1 col-12 p-0">
            <div class="splide w-100" id="splide3">
                <div class="splide__track">
                    <ul class="splide__list pb-1">
                        @foreach($coachs as $coach)
                        <li class="splide__slide px-3">
                            <div class="coach-featured__wrapper">
                                <div class="img-shape">
                                    <a href="#">
                                        <img
                                            src="{{ $coach->image_url ? $coach->image_url :'/assets/cms/assets/img/coach.png' }}">
                                    </a>
                                </div>
                                <div class="text__wrapper px-4">
                                    <div class="text-shape px-2 px-lg-3 pt-4 pb-4">
                                        <a href="#" target="_blank">
                                            <img class="social-media-img__professional-coach"
                                                src="{{ asset('cms/assets/img/instagram-logo.png') }}" alt="">
                                        </a>
                                        <h3 class="color-primary">{{ $coach->name ? $coach->name :'' }}</h3>
                                        <h5 class="mt-3">{{ $coach->expertise_name ? $coach->expertise_name :'' }}</h5>
                                        <p class="mt-4 coach-desc">{{ $coach->description ? $coach->description :'' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
@include('cms.homepage.script')
@endpush
