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
          <li class="active package" data-id="2">Regular Class</li>
          <li class="package" data-id="1">Special Class</li>
          <li class="package" data-id="3">Master Lesson</li>
        </ul>
      </div>

      <div class="class-filter__wrapper mt-4 mb-5 mb-md-0 p-3 p-md-5">
        <div class="splide pb-4" id="class-splide">
          <div class="splide__track">
            
            <ul class="splide__list" id="classrooms">
              @foreach($regular_classrooms as $regular_classroom)
                <li class="splide__slide px-2 pb-5">
                  <img class="w-100 rounded" src="{{ $regular_classroom->image_url ? $regular_classroom->image_url : '' }}" alt="">
                  <div class="badge-left">
                    <h3 class="mt-4 ml-2">{{ $regular_classroom->name ? $regular_classroom->name : '' }}</h3>
                  </div>
                  <ul class="row-center-start class-tab mt-5 mt-md-4">
                    <li class="active tab-detail" data-id="{{ $regular_classroom->id }}" href="tab-description">Deskripsi</li>
                    <li class="tab-detail" data-id="{{ $regular_classroom->id }}" href="tab-coach">Coach</li>
                    <li class="tab-detail" data-id="{{ $regular_classroom->id }}" href="tab-tools">Tools</li>
                  </ul>

                  <div id="description">
                    <div class="content-tab-detail" style="">
                      <div class="desc__class-tab my-4">
                        <p>
                          {{ $regular_classroom->description ? $regular_classroom->description : '' }}
                        </p>
                      </div>
                    </div>
                    <div class="class-tab-summary d-flex justify-content-between flex-md-row flex-column mb-4">
                      <div class="d-flex flex-column">
                        <p>{{ $regular_classroom->session_total ? $regular_classroom->session_total : '' }} Sesi | @ {{ $regular_classroom->session_duration ? $regular_classroom->session_duration : '' }}menit</p>
                        <span class="mt-2">Rp. {{ $regular_classroom->price ? $regular_classroom->price : '' }}</span>
                      </div>
                      <div class="mt-5 mt-md-0">
                        <a class="btn btn-primary shadow" onclick="@if (Auth::guard('student')->user()){{'showModalRegisterClassroom("'.$regular_classroom->id.'")'}}@else{{'showModalLoginRequired()'}}@endif">Daftar
                          Sekarang
                          <img class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}" alt="">
                        </a>
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
    <div class="col-xl-8 order-1 order-xl-2 mt-3 mt-md-0">
      <div class="splide mb-4" id="category-splide">
        <div class="splide__track">
          <ul class="splide__list">
            @foreach($classroom_categories as $classroom_category)
            @if($classroom_category->id == $selected_category->id)
            <li class="splide__slide px-2">
              <div class="class-category-filter__wrapper class-category-selected" data-id="{{$classroom_category->id}}">
                <div class="class-category-filter-overlay row-center ">
                  <h4>{{ $classroom_category->name ? $classroom_category->name:'' }}</h4>
                </div>
                <img src="{{ asset('cms/assets/img/category-placeholder.png') }}" alt="">
              </div>
            </li>
            @else
            <li class="splide__slide px-2">
              <div class="class-category-filter__wrapper" data-id="{{$classroom_category->id}}">
                <div class="class-category-filter-overlay row-center">
                  <h4>{{ $classroom_category->name ? $classroom_category->name:'' }}</h4>
                </div>
                <img src="{{ asset('cms/assets/img/category-placeholder.png') }}" alt="">
              </div>
            </li>
            @endif
            @endforeach
          </ul>
        </div>
      </div>
      <div class="sub-category" id="sub-category">
        @foreach($sub_categories as $sub_category)
        @if($sub_category->id == $selected_sub_category->id)
        <button class="btn btn-tertiary mr-2 mb-2 active" data-id="{{$sub_category->id}}">{{ $sub_category->name ? $sub_category->name:''}}</button>
        @else
        <button class="btn btn-tertiary mr-2 mb-2" data-id="{{$sub_category->id}}">{{ $sub_category->name ? $sub_category->name:''}}</button>
        @endif
        @endforeach
      </div>

      <div id="empty-classroom">

      </div>

      <div class="splide mt-5" id="class-category-splide">
        <div class="splide__track">
          <ul class="splide__list" id="classroom-content">

            @foreach($classrooms as $classroom)
            <li class="splide__slide pb-md-0 pb-3 pb-md-5">
              <div class="content-embed__wrapper">
                <img src="{{ $classroom->image_url ? $classroom->image_url :'' }}"
                data-splide-lazy="path-to-the-image" alt="">
                <div class="px-4 px-md-0 px-md-0 pt-4 pt-md-0">
                  <div class="badge-left">
                    <h3 class="mt-3 ml-2">{{ $classroom->name ? $classroom->name:''}}</h3>
                  </div>
                  <p class="my-3 desc__slider-content">{{ $classroom->description ? $classroom->description:''}}</p>
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
@include('cms.class.modal')
@endsection

@push('script')
@include('cms.class.script')
@endpush