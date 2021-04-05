@extends('cms.layouts.app')
@section('content')
<section>
    <div class="row">
      <div class="col-lg-4 col-12 mx-0 pr-5 px-md-0 pr-lg-5 login-aside" data-aos="zoom-out">
        <div class="login-wrapper p-5">
          <img class="rounded-circle img-logged mb-3" src="{{ asset('cms/assets/img/logo.png') }}" alt="">
          <h1>Platform Pembelajaran Seni Pertama di Indonesia</h1>
          <p class="mt-3 text-justify">Mejaseni merupakan platform daring khusus kursus kesenian secara profesional. Setiap kursus di
            Mejaseni didampingi oleh profesional ahli dan berpengalaman di bidangnya. Dengan metode pengajaran daring
            yang menyenangkan, praktis, dan tepat sasaran, maka kamu dipastikan mampu menguasai kemampuan berkarya baru
            sesuai bakat dan minatmu.</p>
          <div class="pt-4">
            <a href="{{ url('register') }}" class="btn btn-primary row-center w-75">
              Daftar Sekarang<img class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}" alt=""></a>
          </div>
        </div>
      </div>
      <div class="col-lg-8 col-12 px-0 mt-0 mt-md-5 mt-lg-0">
        <div class="splide pb-md-5 pb-3 mb-5" id="splide1">
          <div class="splide__track">
            <ul class="splide__list">
              @foreach($galeries as $galery)
                <li class="splide__slide pb-md-0 pb-5">
                  <div class="content-embed__wrapper">
                    <img src="{{ isset($galery->image_url) ? $galery->image_url:'' }}" data-splide-lazy="path-to-the-image" alt="">
                    <div class="px-5 px-md-0 pt-4 pt-md-0">
                      <div class="badge-left">
                        <h3 class="mt-3 ml-2">{{ isset($galery->title) ? $galery->title:'' }}</h3>
                      </div>
                      <p class="my-3 desc__slider-content text-justify">{{ isset($galery->description) ? $galery->description:'' }}</p>
                      <a href="{{ url('galery') }}/{{ $galery->id}}/detail">Selengkapnya ></a>
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

  <section id="class-category" class="pb-5">
    <div class="row py-5">
      <div class="col-lg-3">
        <h1 class="color-white mt-3 mb-5 pt-md-0 pt-5 text-center">Visi & Misi Kami</h1>
      </div>
      <div class="col-lg-9">
        <div class="row">
          <div class="col-lg-6 p-4">
            <h2 class="color-white">Visi</h2>
            <p class="mt-3 text-justify" >{{ isset($company->vision) ? $company->vision : ''}}</p>
          </div>
          <div class="col-lg-6 p-4">
            <h2 class="color-white">Misi</h2>
            <p class="mt-3 text-justify">{{ isset($company->mission) ? $company->mission : ''}}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="program-package">
    <div class="row my-5 pb-5">
      <div class="col-md-12 text-center my-5 pt-4 pt-md-0 pb-2 pb-md-5">
        <h1>Mengapa Harus Kami?</h1>
      </div>
      @foreach($reasons as $reason)
        <div class="col-md-4 col-sm-12 mb-md-5 px-5 px-md-3 py-3 py-md-0">
          <div class="our-program__item p-5" data-aos="zoom-out" data-aos-delay="0">
            <img class="img__program-thumbnail" src="{{ isset($reason->image_url) ? $reason->image_url:'' }}" alt="">
            <div class="badge-left">
              <h3 class="mt-3 ml-2">{{ isset($reason->name) ? $reason->name:'' }}</h3>
            </div>
            <p class="my-3 pt-1 text-justify">{{ isset($reason->description) ? $reason->description:'' }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  <section id="professional-coach">
    <div class="row d-flex pb-5">
      <div class="col-lg-3 col-12 my-5 pt-4 pt-md-0 pb-2 pb-lg-5 text-md-center">
        <h1>Tim Kami</h1>
      </div>
      <div class="col-lg-9 col-12 p-0">
        <div class="splide pb-5" id="our-team">
          <div class="splide__track pb-5">
            <ul class="splide__list pb-1">
              @foreach($teams as $team)
                <li class="splide__slide px-3">
                  <div class="row">
                    <div class="col-lg-4">
                      <img class="w-100 rounded-large" src="{{ isset($team->image_url) ? $team->image_url:''}}">
                    </div>
                    <div class="col-lg-8 pl-4 pr-5 text-lg-left text-center">
                      <h1 class="mb-2 mt-lg-0 mt-5">{{ isset($team->name) ? $team->name:''}}</h1>
                      <h3>{{ isset($team->position) ? $team->position:''}}</h3>
                      <p class="mt-3 text-justify">{{ isset($team->description) ? $team->description:''}}</p>
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

  <section id="operational-office">
    <div class="row">
      <div class="col-lg-3 mb-md-4 py-5 py-md-0 text-lg-left text-center">
        <h1>Come Visit Our Workshop</h1>
      </div>
      <div class="col-lg-6 pr-3 pr-md-4">
        <div class="maps-wrapper">
          <iframe
            src="{{ isset($company->maps_url) ? $company->maps_url:'' }}"
            width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
            tabindex="0"></iframe>
        </div>
      </div>
      <div class="col-lg-3 col-md-10 col-12 my-4 mt-0 mt-md-5 mt-lg-0 ml-0 ml-md-5 ml-lg-0 p-5 p-md-0">
        <div class="badge-left pl-2">
          <h3>Working Hours</h3>
        </div>

        @foreach($working_hours as $working_hour)
          <div class="row mt-4">
            <div class="col-6">{{ isset($working_hour->day) ? $working_hour->day:''}}</div>
            @if($working_hour->is_closed)
              <div class="col-6">Closed</div>
            @else
              <div class="col-6">
                {{ isset($working_hour->open) ? date('h:i A', strtotime($working_hour->open)):''}} -
                {{ isset($working_hour->close) ? date('h:i A', strtotime($working_hour->close)):''}}
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection
@push('script')
  @include('cms.about.script')
@endpush
