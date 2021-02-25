@extends('cms.layouts.app')

@push('banner')
@include('cms.layouts.banner')
@endpush

@section('content')
<section class="mb-5 pb-3 mb-md-0 pb-md-0">
  <div class="row">
    <div class="col-lg-3 order-2 order-lg-1">
      <div class="store-category__wrapper px-3 py-4 py-md-5 px-lg-4 px-xl-5 mt-3 mb-5 mt-md-0 ">
        <div class="column-center-start w-100">
          <ul>
            <a href="#">
              <li class="active">Video Course</li>
            </a>
            <a href="#">
              <li>Course Tools</li>
            </a>
            <a href="#">
              <li>Music Instrument</li>
            </a>
            <a href="#">
              <li>Art Product</li>
            </a>
            <a href="#">
              <li>Merchandhise</li>
            </a>
          </ul>
        </div>
        <div class="online-shop row-center">
          <div class="online-shop__item-wrapper">
            <div class="online-shop__item mb-2">
              <a href="#" target="_blank">
                <img src="{{ asset('cms/assets/img/svg/tokopedia-logo.svg') }}" alt="">
              </a>
            </div>
            <span>Tokopedia</span>
          </div>
          <div class="online-shop__item-wrapper">
            <a href="#" target="_blank">
              <div class="online-shop__item mb-2">
                <img src="{{ asset('cms/assets/img/svg/shopee-logo.svg') }}" alt="">
              </div>
            </a>
            <span>Shopee</span>
          </div>
          <div class="online-shop__item-wrapper">
            <div class="online-shop__item mb-2">
              <a href="#" target="_blank">
                <img src="{{ asset('cms/assets/img/svg/blibli-logo.svg') }}" alt="">
              </a>
            </div>
            <span>Blibli</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-9 order-1 order-lg-2 mt-3 mt-md-0">
      <div class="mb-5">
        <input class="form-control input-rounded" list="datalistOptions" id="exampleDataList"
        placeholder="Type to search...">
        <datalist id="datalistOptions">
          <option value="San Francisco">
            <option value="New York">
              <option value="Seattle">
                <option value="Los Angeles">
                  <option value="Chicago">
                  </datalist>
                </div>
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
                    </ul>
                  </div>
                </div>
                <div class="sub-category py-4">
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
                  <button class="btn btn-tertiary mr-2 mb-2">Piano</button>
                  <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
                  <button class="btn btn-tertiary mr-2 mb-2">Guitar</button>
                  <button class="btn btn-tertiary mr-2 mb-2">Saxophone</button>
                </div>
                <div class="store-content__wrapper mt-4">
                  <div class="shine-hover">
                    
                    <div class="row mb-5 pb-2">
                      <div class="col-xl-3 mb-3 mb-md-0">
                        <a href="#">
                          <figure><img src="{{ asset('cms/assets/img/master-lesson__banner.jpg') }}" /></figure>
                        </a>
                      </div>
                      <div class="col-xl-9 px-4">
                        <div class="badge-left">
                          <a href="store-detail.html" target="_blank">
                            <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">Lorem ipsum, dolor sit amet consectetur
                              adipisicing elit. Lorem
                            ipsum, dolor sit amet consectetur adipisicing elit. Enim, consequatur.</h3>
                          </a>
                        </div>
                        <p class="mt-3 ml-3 desc__store-content">Lorem ipsum dolor sit amet consectetur
                          adipisicing elit. Quaerat cum,
                          laboriosam
                          saepe libero, eaque laborum natus similique iure totam illo placeat ipsa nostrum
                          doloremque eligendi dolore aliquam modi temporibus enim sed dicta accusamus?
                          Deserunt quos accusantium voluptatem distinctio dolor ex excepturi ullam officia,
                          nobis tempore eveniet, incidunt cum recusandae vitae inventore unde laboriosam
                          mollitia, illo reprehenderit. Laboriosam quos odio perferendis excepturi voluptas.
                        Ipsa aspernatur nulla at distinctio facilis vel,!</p>
                        <div class="detail__store-content ml-3 mt-3">
                          <div class="coach-name__store-content row-center mr-4">
                            <img src="{{ asset('cms/assets/img/svg/User.svg') }}" class="mr-2" alt="">Rista Amelia Baskoro
                          </div>
                          <div class="class__store-content row-center mt-md-0 mt-3">
                            <img src="{{ asset('cms/assets/img/svg/Crown.svg') }}" class="mr-2" alt="">Intermediate Piano
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-5 pb-2">
                      <div class="col-xl-3 mb-3 mb-md-0">
                        <a href="#">
                          <figure><img src="{{ asset('cms/assets/img/master-lesson__banner.jpg') }}" /></figure>
                        </a>
                      </div>
                      <div class="col-xl-9 px-4">
                        <div class="badge-left">
                          <a href="store-detail.html" target="_blank">
                            <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">Lorem ipsum, dolor sit amet consectetur
                              adipisicing elit. Lorem
                            ipsum, dolor sit amet consectetur adipisicing elit. Enim, consequatur.</h3>
                          </a>
                        </div>
                        <p class="mt-3 ml-3 desc__store-content">Lorem ipsum dolor sit amet consectetur
                          adipisicing elit. Quaerat cum,
                          laboriosam
                          saepe libero, eaque laborum natus similique iure totam illo placeat ipsa nostrum
                          doloremque eligendi dolore aliquam modi temporibus enim sed dicta accusamus?
                          Deserunt quos accusantium voluptatem distinctio dolor ex excepturi ullam officia,
                          nobis tempore eveniet, incidunt cum recusandae vitae inventore unde laboriosam
                          mollitia, illo reprehenderit. Laboriosam quos odio perferendis excepturi voluptas.
                        Ipsa aspernatur nulla at distinctio facilis vel,!</p>
                        <div class="detail__store-content ml-3 mt-3">
                          <div class="coach-name__store-content row-center mr-4">
                            <img src="{{ asset('cms/assets/img/svg/User.svg') }}" class="mr-2" alt="">Rista Amelia Baskoro
                          </div>
                          <div class="class__store-content row-center mt-md-0 mt-3">
                            <img src="{{ asset('cms/assets/img/svg/Crown.svg') }}" class="mr-2" alt="">Intermediate Piano
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-5 pb-2">
                      <div class="col-xl-3 mb-3 mb-md-0">
                        <a href="#">
                          <figure><img src="{{ asset('cms/assets/img/master-lesson__banner.jpg') }}" /></figure>
                        </a>
                      </div>
                      <div class="col-xl-9 px-4">
                        <div class="badge-left">
                          <a href="store-detail.html" target="_blank">
                            <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">Lorem ipsum, dolor sit amet consectetur
                              adipisicing elit. Lorem
                            ipsum, dolor sit amet consectetur adipisicing elit. Enim, consequatur.</h3>
                          </a>
                        </div>
                        <p class="mt-3 ml-3 desc__store-content">Lorem ipsum dolor sit amet consectetur
                          adipisicing elit. Quaerat cum,
                          laboriosam
                          saepe libero, eaque laborum natus similique iure totam illo placeat ipsa nostrum
                          doloremque eligendi dolore aliquam modi temporibus enim sed dicta accusamus?
                          Deserunt quos accusantium voluptatem distinctio dolor ex excepturi ullam officia,
                          nobis tempore eveniet, incidunt cum recusandae vitae inventore unde laboriosam
                          mollitia, illo reprehenderit. Laboriosam quos odio perferendis excepturi voluptas.
                        Ipsa aspernatur nulla at distinctio facilis vel,!</p>
                        <div class="detail__store-content ml-3 mt-3">
                          <div class="coach-name__store-content row-center mr-4">
                            <img src="{{ asset('cms/assets/img/svg/User.svg') }}" class="mr-2" alt="">Rista Amelia Baskoro
                          </div>
                          <div class="class__store-content row-center mt-md-0 mt-3">
                            <img src="{{ asset('cms/assets/img/svg/Crown.svg') }}" class="mr-2" alt="">Intermediate Piano
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
          @include('cms.store.script')
          @endpush