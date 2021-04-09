@extends('cms.layouts.app')

@push('banner')
@include('cms.layouts.banner')
@endpush

@section('content')
<section class="mb-5 pb-3 mb-md-0 pb-md-0">
  <div class="row">
    <div class="col-lg-12 order-1 order-lg-2 mt-3 mt-md-0">
      <div class="mb-5">
        <input class="form-control input-rounded" list="datalistOptions" id="search" placeholder="Type to search...">
        <datalist id="datalistOptions">
        </datalist>
        </div>
        @if(!$classroom_categories->isEmpty())
        <div class="splide mb-4" id="category-splide">
          <div class="splide__track">
            <ul class="splide__list">
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
                  <div class="class-category-filter-overlay row-center" >
                    <h4>{{ isset($classroom_category->name) ? $classroom_category->name:'' }}</h4>
                  </div>
                  <img src="{{ asset('cms/assets/img/category-placeholder.png') }}" alt="">
                </div>
              </li>
              @endif
              @endforeach
            </ul>
          </div>
        </div>
        <div class="sub-category py-4" id="sub-category">
          @foreach($sub_categories as $sub_category)
            @if($loop->index == 0)
              <button class="btn btn-tertiary mr-2 mb-2 active" data-id="{{$sub_category->id}}">{{ isset($sub_category->name) ? $sub_category->name:''}}</button>
            @else
              <button class="btn btn-tertiary mr-2 mb-2" data-id="{{$sub_category->id}}">{{ isset($sub_category->name) ? $sub_category->name:''}}</button>
            @endif
          @endforeach

        </div>

        <div id="empty-video-course"></div>

        <div class="store-content__wrapper mt-4">
          <div class="shine-hover" id="video_courses">
            @if(!$video_courses->isEmpty())
              @foreach($video_courses as $video_course)
                <div class="row mb-5 pb-2">
                  <div class="col-xl-3 mb-3 mb-md-0">
                    <a href="#">
                      <figure><img src="{{ isset($video_course->image_url) ? $video_course->image_url:"" }}" /></figure>
                    </a>
                  </div>
                  <div class="col-xl-9 px-4">
                    <div class="badge-left">
                      <a href="{{ url('video-course') }}/{{$video_course->id}}/detail" target="_blank">
                        <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">{{ isset($video_course->name) ? $video_course->name:''}}</h3>
                      </a>
                    </div>
                    <p class="mt-3 ml-3 desc__store-content text-justify">{{ isset($video_course->description) ? $video_course->description:''}}</p>
                    <div class="detail__store-content ml-3 mt-3">
                      <div class="coach-name__store-content row-center mr-4">
                        <img src="{{ asset('cms/assets/img/svg/User.svg') }}" class="mr-2" alt="">{{ isset($video_course->coach) ? $video_course->coach:''}}
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
            @else
              <div class="mb-5 empty-store">
                  <div class="row my-5 py-5">
                      <div class="col-12 pr-0 pr-lg-4 column-center">
                          <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
                          <h4 class="mt-3 text-center">Wah, video course yang kamu cari <br />belum dibuat nih</h4>
                      </div>
                  </div>
              </div>
            @endif

          </div>
        </div>

        @else
          <div class="mb-5 empty-store">
            <div class="row my-5 py-5">
                <div class="col-12 pr-0 pr-lg-4 column-center">
                    <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
                    <h4 class="mt-3 text-center">Wah, video course yang kamu cari <br />belum dibuat nih</h4>
                </div>
            </div>
        </div>
        @endif

      </div>
    </div>
  </section>

  @endsection

  @push('script')
  @include('cms.video-course.script')
  @endpush