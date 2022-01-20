@extends('cms.layouts.app')

@push('banner')
@include('cms.layouts.banner')
@endpush

@section('content')
<section>
  <div class="row mx-0">
    <div class="col-lg-12 col-md-12 col-sm-12 px-0 mt-0 mt-md-5 mt-lg-0">
      @if(!$store_banners->isEmpty())
      <div class="splide pb-md-5 pb-3 mb-5 w-100" id="store-banner-splide">
        <div class="splide__track">
          <ul class="splide__list">
            @foreach($store_banners as $store_banner)
            <li class="splide__slide pb-md-0 pb-5">
              <div class="content-embed__wrapper">
                <img src="{{ isset($store_banner->image_url) ? $store_banner->image_url:''}}"
                data-splide-lazy="path-to-the-image" alt="">
              </div>
            </li>
            @endforeach
          </ul>
        </div>
      </div>

      @else
      <div class="col-12 pr-0 pr-lg-4 mt-4 column-center">
        <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
        <h4 class="mt-3 text-center">Wah, Gambar belum ditambahkan</h4>
      </div>
      @endif

    </div>

    <div class="col-xl-12 col-md-12 col-sm-12">
      <div class="row justify-content-center">
        @foreach($market_places as $market_place)
        <div class="col-xl-4">
          <div class="d-flex justify-content-center">
            <div class="card w-50 m-3" style="border-radius: 1.25rem;">
              <div class="card-body">
                <div class="d-flex align-items-center flex-column">
                  <a href="{{ isset($market_place->url) ? $market_place->url:''}}" target="_blank">
                  <img src="{{ isset($market_place->image_url) ? $market_place->image_url:''}}" style="height: 5rem;" alt="">
                </a>
                <span class="mt-2">{{ isset($market_place->name) ? $market_place->name:''}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach 
      </div>
    </div>


  </div>
</section>

@endsection

@push('script')
@include('cms.store.script')
@endpush