

@extends('cms.layouts.app')

@push('banner')
@include('cms.layouts.banner')
@endpush

@push('style')
<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
@endpush

@section('content')
<section id="news-wrapper">
        <div class="row column-center">
            <div class="col-12 column-center">
                <img class="w-100 news-img__wrapper" src="{{ isset($galery->image_url) ? $galery->image_url:'' }}" alt="">
            </div>
            <div class="col-12 col-lg-8 column-center mb-0 pb-0">
                <div class="w-100 news-detail mb-0 pb-0">
                    <div class="w-100 news-detail__overlay p-5">
                        <h3>{{ isset($galery->title) ? $galery->title:'' }}</h3>
                        <h5 class="pt-4 mt-2 pb-4">{{ isset($galery->date) ? date_format(date_create($galery->date), "l, d F Y"):''}}</h5>
                        <div class="ql-editor">
                            {!! $galery->description !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="splide" id="other-news">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach($galeries as $galery)

                                <li class="splide__slide px-3 pb-md-0 pb-3 pb-md-5">
                                    <a href="{{ url('galery') }}/{{ $galery->id }}/detail">
                                        <img src="{{ isset($galery->image_url) ? $galery->image_url:'' }}"
                                            data-splide-lazy="path-to-the-image" alt="">
                                        <div class="px-4 px-md-0 px-md-0 pt-4 pt-md-0">
                                            <h5 class="mt-2">{{ isset($galery->title) ? $galery->title:'' }}</h5>
                                        </div>
                                    </a>
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
@include('cms.news-detail.script')
@endpush
