@extends('cms.layouts.app')

@push('banner')
  @include('cms.layouts.banner')
@endpush

@section('content')
<section>
  <div class="row">
    <div class="col-xl-4 order-2 order-xl-1">
      <div class="package-filter__wrapper mt-3 mt-md-0">
        <ul>
          <li class="active package">Regular Class</li>
          <li class="package">Special Class</li>
          <li class="package">Master Lesson</li>
        </ul>
      </div>
      <div class="class-filter__wrapper mt-4 mb-5 mb-md-0 p-3 p-md-5">
        <div class="splide pb-4" id="class-splide">
          <div class="splide__track">
            <ul class="splide__list">
              @foreach($classrooms as $classroom)
                <li class="splide__slide px-2 pb-5">
                  <img class="w-100 rounded" src="{{ $classroom->image_url ? $classroom->image_url : '' }}" alt="">
                  <div class="badge-left">
                    <h3 class="mt-4 ml-2">{{ $classroom->name ? $classroom->name : '' }}</h3>
                  </div>
                  <ul class="row-center-start class-tab mt-5 mt-md-4">
                    <li class="active">Deskripsi</li>
                    <li>Coach</li>
                    <li>Tools</li>
                  </ul>
                  <div class="desc__class-tab my-4">
                    <p>
                      {{ $classroom->description ? $classroom->description : '' }}
                    </p>
                  </div>
                  <div class="coach__class-tab my-4">
                    <div class="row-center-start">
                      <div class="coach-img__class-tab mr-3">
                        <img src="{{ asset('cms/assets/img/coach.png') }}" class="w-100 rounded-circle" alt="">
                      </div>
                      <div class="d-flex flex-column">
                        <h3>Rista Amelia Baskoro</h3>
                        <span class="mt-1">Pianist / Keyboardist</span>
                      </div>
                    </div>
                  </div>
                  <div
                  class="class-tab-summary d-flex justify-content-between flex-md-row flex-column mb-4">
                  <div class="d-flex flex-column">
                    <p>{{ $classroom->session_total ? $classroom->session_total : '' }} Sesi | @ {{ $classroom->session_duration ? $classroom->session_duration : '' }}menit</p>
                    <span class="mt-2">Rp. {{ $classroom->price ? $classroom->price : '' }},-</span>
                  </div>
                  <div class="mt-5 mt-md-0">
                    <a href="#" class="btn btn-primary shadow registerNow">Daftar
                      Sekarang
                      <img class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}" alt=""></a>
                    </div>
                  </div>
                </li>
              @endforeach
              

              {{-- <li class="splide__slide px-2 pb-5">
                <img class="w-100 rounded" src="{{ asset('cms/assets/img/master-lesson__banner.jpg') }}" alt="">
                <div class="badge-left">
                  <h3 class="mt-3 ml-2">Basic Piano</h3>
                </div>
              </li>
              <li class="splide__slide px-2 pb-5">
                <img class="w-100 rounded" src="{{ asset('cms/assets/img/master-lesson__banner2.jpg') }}" alt="">
                <div class="badge-left">
                  <h3 class="mt-3 ml-2">Basic Piano</h3>
                </div>
              </li> --}}
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-8 order-1 order-xl-2 mt-3 mt-md-0">
      <div class="splide mb-4" id="category-splide">
        <div class="splide__track">
          <ul class="splide__list">
            @foreach($classroom_categories as $classroom_category)
              <li class="splide__slide px-2">
                <div class="class-category-filter__wrapper">
                  <div class="class-category-filter-overlay row-center">
                    <h4>{{ $classroom_category->name ? $classroom_category->name:'' }}</h4>
                  </div>
                  <img src="{{ asset('cms/assets/img/category-placeholder.png') }}" alt="">
                </div>
              </li>
            @endforeach
          </div>
        </div>
        <div class="sub-category">
          <button class="btn btn-tertiary mr-2 mb-2">Piano</button>
          <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
          <button class="btn btn-tertiary active mr-2 mb-2">Guitar</button>
          <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
          <button class="btn btn-tertiary mr-2 mb-2">Piano</button>
          <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
          <button class="btn btn-tertiary mr-2 mb-2">Guitar</button>
          <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
          <button class="btn btn-tertiary mr-2 mb-2">Piano</button>
          <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
          <button class="btn btn-tertiary mr-2 mb-2">Guitar</button>
          <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
          <button class="btn btn-tertiary mr-2 mb-2">Piano</button>
          <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
          <button class="btn btn-tertiary mr-2 mb-2">Guitar</button>
          <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
          <button class="btn btn-tertiary mr-2 mb-2">Piano</button>
          <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
          <button class="btn btn-tertiary mr-2 mb-2">Guitar</button>
          <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
        </div>
        <div class="splide mt-5" id="class-category-splide">
          <div class="splide__track">
            <ul class="splide__list">
              <li class="splide__slide pb-md-0 pb-3 pb-md-5">
                <div class="content-embed__wrapper">
                  <img src="{{ asset('cms/assets/img/master-lesson__banner.jpg') }}"
                  data-splide-lazy="path-to-the-image" alt="">
                  <div class="px-4 px-md-0 px-md-0 pt-4 pt-md-0">
                    <div class="badge-left">
                      <h3 class="mt-3 ml-2">Mejaseni : Profil Coach Piano bersama Erik Chandra
                      Kesuma</h3>
                    </div>
                    <p class="my-3 desc__slider-content">Lorem ipsum dolor sit amet consectetur
                      adipisicing elit. Amet
                      maiores saepe dolore
                      molestias,
                      molestiae sapiente aperiam odio in dicta reiciendis quaerat eligendi facere
                      culpa nemo
                      consequuntur delectus
                      porro tempore aut possimus cum quidem dolores quis. Laborum ad corporis
                      eaque quia commodi ab
                      nisi!
                      Accusamus maxime nulla quod a rerum, sequi aperiam voluptatem excepturi
                      officiis expedita,
                      repellendus,
                      aspernatur velit asperiores. Reiciendis nostrum quam optio dolore, fugit
                      vero obcaecati explicabo.
                      Quis
                      tempore nemo commodi culpa deleniti molestiae iste recusandae labore ipsa
                      illo provident tempora
                      vero,
                      necessitatibus excepturi libero minima aspernatur eius similique ipsum ex?
                      Velit et maxime numquam
                      quidem,
                    beatae veritatis iusto.</p>
                  </div>
                </div>
              </li>
              <li class="splide__slide pb-md-0 pb-3 pb-md-5">
                <div class="content-embed__wrapper">
                  <img src="{{ asset('cms/assets/img/master-lesson__banner.jpg') }}"
                  data-splide-lazy="path-to-the-image" alt="">
                  <div class="px-5 px-md-0 pt-4 pt-md-0">
                    <div class="badge-left">
                      <h3 class="mt-3 ml-2">Mejaseni : Profil Coach Piano bersama Erik Chandra
                      Kesuma</h3>
                    </div>
                    <p class="my-3 desc__slider-content">Lorem ipsum dolor sit amet consectetur
                      adipisicing elit. Amet
                      maiores saepe dolore
                      molestias,
                      molestiae sapiente aperiam odio in dicta reiciendis quaerat eligendi facere
                      culpa nemo
                      consequuntur delectus
                      porro tempore aut possimus cum quidem dolores quis. Laborum ad corporis
                      eaque quia commodi ab
                      nisi!
                      Accusamus maxime nulla quod a rerum, sequi aperiam voluptatem excepturi
                      officiis expedita,
                      repellendus,
                      aspernatur velit asperiores. Reiciendis nostrum quam optio dolore, fugit
                      vero obcaecati explicabo.
                      Quis
                      tempore nemo commodi culpa deleniti molestiae iste recusandae labore ipsa
                      illo provident tempora
                      vero,
                      necessitatibus excepturi libero minima aspernatur eius similique ipsum ex?
                      Velit et maxime numquam
                      quidem,
                    beatae veritatis iusto.</p>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endsection

@push('script')
  @include('cms.class.script')
@endpush