@extends('cms.layouts.app')

@push('banner')
@include('cms.layouts.banner')
@endpush

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>  
  .checked {  
      color : yellow;  
      font-size : 20px;  
  }  
  .unchecked {  
      font-size : 20px;  
  }  
</style>
@endpush

@section('content')
<section>
  <div class="row">
    <div class="col-xl-4 order-2 order-xl-1">
      <div class="package-filter__wrapper mt-3 mt-md-0">
        <ul>
          <li class="active package" data-id="2">Regular Class</li>
          <li class="package" data-id="1">Special Class</li>
          <li class="package" data-id="3">Master Lesson</li>
        </ul>
      </div>

      <div class="class-filter__wrapper mt-4 mb-5 mb-md-0 p-3 p-md-5">
        <div id="class-content">
          @if(!$regular_classrooms->isEmpty())
          <div class="splide pb-4" id="class-splide">
            <div class="splide__track">
              <ul class="splide__list" id="classrooms">
                @foreach($regular_classrooms as $regular_classroom)
                <li class="splide__slide px-2 pb-5">
                  <img class="w-100 rounded" src="{{ isset($regular_classroom->image_url) ? $regular_classroom->image_url : '' }}" alt="">
                  <div class="badge-left">
                    <h3 class="mt-4 ml-2">{{ isset($regular_classroom->name) ? $regular_classroom->name : '' }}</h3>
                  </div>
                  <ul class="row-center-start class-tab mt-5 mt-md-4">
                    <li class="active tab-detail" data-id="{{ $regular_classroom->id }}" href="tab-description">Deskripsi</li>
                    <li class="tab-detail" data-id="{{ $regular_classroom->id }}" href="tab-coach">Coach</li>
                    <li class="tab-detail" data-id="{{ $regular_classroom->id }}" href="tab-tools">Tools</li>
                  </ul>

                  <div id="description">
                    <div class="content-tab-detail" style="">
                      <div class="desc__class-tab my-4">
                        <p class="text-justify readmore">
                          {{ isset($regular_classroom->description) ? $regular_classroom->description : '' }}
                        </p>
                      </div>
                    </div>
                    <div class="class-tab-summary mb-4">
                      <div class="row">
                        <div class="col col-12 mt-4">
                          <div class="d-flex flex-column"> 
                            <p>{{ isset($regular_classroom->session_total) ? $regular_classroom->session_total : '' }} Sesi | @ {{ isset($regular_classroom->session_duration) ? $regular_classroom->session_duration : '' }}menit |
                              <span class="fa fa-star checked mr-1" style="font-size: 15px;"></span> {{ isset($regular_classroom->rating) ? $regular_classroom->rating < 4 ? '4.6':$regular_classroom->rating : '4.6' }}
                          </p>
                            <span class="mt-2">Rp.@convert($regular_classroom->price)</span>
                            
                            
                          </div>
                        </div>
                        <div class="col col-12 mt-4 justify-content-md-end">
                          <div class="d-flex justify-content-start">
                            <div class="mr-2">
                              <a class="btn btn-primary shadow" onclick="@if (Auth::guard('student')->user()){{'showModalRegisterClassroom("'.$regular_classroom->id.'")'}}@else{{'showModalLoginRequired()'}}@endif">Daftar
                                Sekarang
                                <img class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}">
                              </a>
                            </div>
                            <div>
                              <a class="btn btn-primary shadow" onclick="showModalClass()">Lihat Kelas
                                <img class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}">
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </li>
                @endforeach

              </ul>
            </div>
          </div>
          @else
          <div class="col-12 pr-0 pr-lg-4 column-center">
            <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
            <h4 class="mt-3 text-center">Wah, Class belum tersedia</h4>
          </div>
          @endif
        </div>
      </div>
    </div>
    <div class="col-xl-8 order-1 order-xl-2 mt-3 mt-md-0">
      <div class="splide mb-4" id="category-splide">
        <div class="splide__track">
          <ul class="splide__list">
            @if(!$classroom_categories->isEmpty())
              @foreach($classroom_categories as $classroom_category)
                @if($classroom_category->id == $selected_category->id)
                <li class="splide__slide px-2">
                  <div class="class-category-filter__wrapper class-category-selected" data-id="{{$classroom_category->id}}">
                    <div class="class-category-filter-overlay row-center ">
                      <h4>{{ isset($classroom_category->name) ? $classroom_category->name:'' }}</h4>
                    </div>
                    <img src="{{ asset('cms/assets/img/category-placeholder.png') }}" alt="">
                  </div>
                </li>
                @else
                <li class="splide__slide px-2">
                  <div class="class-category-filter__wrapper" data-id="{{$classroom_category->id}}">
                    <div class="class-category-filter-overlay row-center">
                      <h4>{{ isset($classroom_category->name) ? $classroom_category->name:'' }}</h4>
                    </div>
                    <img src="{{ asset('cms/assets/img/category-placeholder.png') }}" alt="">
                  </div>
                </li>
                @endif
              @endforeach
            @endif
          </ul>
        </div>
      </div>
      <div class="sub-category" id="sub-category">
        @if(!$sub_categories->isEmpty())
          @foreach($sub_categories as $sub_category)
            @if($sub_category->id == $selected_sub_category->id)
              <button class="btn btn-tertiary mr-2 mb-2 active" data-id="{{$sub_category->id}}">{{ $sub_category->name ? $sub_category->name:''}}</button>
            @else
              <button class="btn btn-tertiary mr-2 mb-2" data-id="{{$sub_category->id}}">{{ $sub_category->name ? $sub_category->name:''}}</button>
            @endif
          @endforeach
        @endif
      </div>

      <div id="classroom-content">
        @if(isset($selected_sub_category))
          
          @if(isset($selected_sub_category->is_youtube))
            @if($selected_sub_category->is_youtube)
              <div class="mb-3">
                <div class="embed-responsive embed-responsive-16by9">
                  <iframe class="embed-responsive-item" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen src="{{ $selected_sub_category->url }}" id="video-coach-review"></iframe>
                </div>
                <div class="px-4 px-md-0 px-md-0 pt-4 pt-md-0">
                  <div class="badge-left">
                    <h3 class="mt-3 ml-2">{{ $selected_sub_category->coach ? $selected_sub_category->coach:''}}</h3>
                  </div>
                  <p class="my-3 desc__slider-content text-justify">{{ $selected_sub_category->description ? $selected_sub_category->description:''}}</p>
                </div>
              </div>

              @else
              <div class="content-embed__wrapper">
                <video id="video-player" class="video-js w-100 h-100 vjs-big-play-centered" controls preload="auto" data-setup='{}'>
                  <source id="video-coach-review" src="{{$selected_sub_category->url}}"></source>
                  <p class="vjs-no-js">
                    To view this video please enable JavaScript, and consider upgrading to a
                    web browser that
                    <a href="http://videojs.com/html5-video-support/" target="_blank">
                      supports HTML5 video
                    </a>
                  </p>
                </video>
                <div class="px-4 px-md-0 px-md-0 pt-4 pt-md-0">
                  <div class="badge-left">
                    <h3 class="mt-3 ml-2">{{ $selected_sub_category->coach ? $selected_sub_category->coach:''}}</h3>
                  </div>
                  <p class="my-3 desc__slider-content text-justify">{{ $selected_sub_category->description ? $selected_sub_category->description:''}}</p>
                </div>
              </div>
            @endif
          @endif 

        @else
          @if(isset($selected_category->url))
            @if(isset($selected_category->is_youtube))
              @if($selected_category->is_youtube)
                <div class="mb-3">
                  <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" frameborder="0"
                      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                      allowfullscreen src="{{ $selected_category->url }}" id="video-coach-review"></iframe>
                  </div>
                  <div class="px-4 px-md-0 px-md-0 pt-4 pt-md-0">
                    <div class="badge-left">
                      <h3 class="mt-3 ml-2">{{ $selected_category->coach ? $selected_category->coach:''}}</h3>
                    </div>
                    <p class="my-3 desc__slider-content text-justify">{{ $selected_category->description ? $selected_category->description:''}}</p>
                  </div>
                </div>

                @else
                <div class="content-embed__wrapper">
                  <video id="video-player" class="video-js w-100 h-100 vjs-big-play-centered" controls preload="auto" data-setup='{}'>
                    <source id="video-coach-review" src="{{$selected_category->url}}"></source>
                    <p class="vjs-no-js">
                      To view this video please enable JavaScript, and consider upgrading to a
                      web browser that
                      <a href="http://videojs.com/html5-video-support/" target="_blank">
                        supports HTML5 video
                      </a>
                    </p>
                  </video>
                  <div class="px-4 px-md-0 px-md-0 pt-4 pt-md-0">
                    <div class="badge-left">
                      <h3 class="mt-3 ml-2">{{ $selected_category->coach ? $selected_category->coach:''}}</h3>
                    </div>
                    <p class="my-3 desc__slider-content text-justify">{{ $selected_category->description ? $selected_category->description:''}}</p>
                  </div>
                </div>
              @endif
            @endif 
          @else
            <div class="mb-5 empty-store">
              <div class="row my-5 py-5">
                  <div class="col-12 pr-0 pr-lg-4 column-center">
                      <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
                      <h4 class="mt-3 text-center">Wah, Video Profile Coach yang kamu cari <br />belum dibuat nih</h4>
                  </div>
              </div>
            </div>
          @endif
        @endif
      </div>

  </div>
</div>
</section>
@include('cms.class.modal')
@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Readmore.js/2.0.2/readmore.min.js"></script>
@include('cms.class.script')
@endpush
