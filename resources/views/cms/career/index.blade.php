@extends('cms.layouts.app')

@section('content')
<section id="career-hero">
    </section>

    <section id="career-gallery" class="pb-5">
        <div class="row py-5 px-0 mx-0">
            <div class="col-md-4 text-center text-md-left column-center">
                <h1 class="color-white text-md-left text-center">Fun & Kreatif!</h1>
                <p class="pt-2 pb-4 pt-md-0 pb-md-0">Kembangkan potensimu bersama kami</p>
            </div>
            <div class="col-md-8">
                <div class="splide" id="gallery__splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <li class="splide__slide px-2 px-md-4">
                                <div class="content-embed__wrapper">
                                    <img src="{{ asset('cms/assets/img/gallery1.png') }}" alt="">
                                </div>
                            </li>
                            <li class="splide__slide px-2 px-md-4">
                                <div class="content-embed__wrapper">
                                    <img src="{{ asset('cms/assets/img/gallery2.png') }}" alt="">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="text-center my-5 pt-5 pb-4">
        <h1>We are Hiring!</h1>
        <p class="my-3">Bergabunglah di Mejaseni</p>
    </div>

    <section id="career" class="mb-5 pb-5">
        <div class="career-item__wrapper">
            <div class="row mt-0 mt-lg-3 py-5 px-3 px-md-5">
                <div class="col-md-12 pt-3 pt-lg-5 pb-3 px-4 px-md-0">
                    <div class="career-badge row-center-start">
                        <img src="{{ asset('cms/assets/img/svg/Tie 1.svg') }}" alt="">
                        <div class="column-center-start">
                            <h3 class="w-100 mb-2">Internal Team</h3>
                            <p>Bergabunglah menjadi pengajar di Mejaseni</p>
                        </div>
                    </div>
                </div>
                @foreach($internal_team_careers as $internal_team_career)
                    <div class="col-lg-6 col-12 px-4 pt-5 pb-1">
                        <div class="career__item px-5 pt-5 pb-4" data-aos="zoom-out" data-aos-delay="0">
                            <h3>{{ $internal_team_career->title ? $internal_team_career->title:''}}</h3>
                            <p class="row-center-start mt-3"><img class="mr-2" src="{{ asset('cms/assets/img/svg/marker.svg') }}"
                                    alt="">{{ $internal_team_career->placement ? $internal_team_career->placement:'-'}}</p>
                            <div class="border-line mt-5 mb-4"></div>
                            <div class="row">
                                <a href="{{ url('career-detail') }}" class="btn btn-primary shadow ml-auto">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($profesional_coach_careers)
                <div class="border-line-bold mt-5 mb-4"></div>
                <div class="row mt-0 mt-lg-3 py-5 px-3 px-md-5">
                    <div class="col-md-12 pt-3 pt-lg-5 pb-3 px-4 px-md-0">
                        <div class="career-badge row-center-start">
                            <img src="{{ asset('cms/assets/img/svg/Tie 1.svg') }}" alt="">
                            <div class="column-center-start">
                                <h3 class="w-100 mb-2">Profesional Coach</h3>
                                <p>Bergabunglah menjadi pengajar di Mejaseni</p>
                            </div>
                        </div>
                    </div>
                    @foreach($profesional_coach_careers as $profesional_coach_career)
                        <div class="col-lg-6 col-12 px-4 pt-5 pb-1">
                            <div class="career__item px-5 pt-5 pb-4" data-aos="zoom-out" data-aos-delay="0">
                                <h3>{{ $profesional_coach_career->title ? $profesional_coach_career->title:''}}</h3>
                                <p class="row-center-start mt-3"><img class="mr-2" src="{{ asset('cms/assets/img/svg/marker.svg') }}"
                                        alt="">{{ $profesional_coach_career->placement ? $profesional_coach_career->placement:'-'}}</p>
                                <div class="border-line mt-5 mb-4"></div>
                                <div class="row">
                                    <a href="{{ url('career-detail') }}" class="btn btn-primary shadow ml-auto">Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            
        </div>
    </section>
@endsection
@push('script')
  @include('cms.career.script')
@endpush