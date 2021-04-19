@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.21/dist/css/splide.min.css">
    <style>
        a:hover{
            color: #7F16A7 !important
        }
        @media only screen and (max-width: 600px) {
            #group-filter {
                width: 100%;
            }
        }
    </style>

@endpush
@section('content')
<div class="splide mb-4" id="category-splide">
    <div class="splide__track">
        <ul class="splide__list">

        </ul>
    </div>
</div>

<div id="category" class="d-flex align-content-start flex-wrap">

</div>
<div id="special-offer" class="mt-5 mb-5">

</div>

<div class="row">
    <div class="col-lg-6">
        <h5 class="mt-5 mb-5">Class Conference Package</h5>
    </div>
    <div class="col-lg-6 d-flex justify-content-end mb-5" >
        <input type="hidden" name="init_class_filter" id="init_class_filter">
        <div class="btn-group" id="group-filter" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-outline-primary filter" data-package_type='1'>Special</button>
            <button type="button" class="btn btn-outline-primary filter" data-package_type='2'>Regular</button>
            <button type="button" class="btn btn-outline-primary master-lesson" data-package_type='3'>Master Lesson</button>
        </div>
    </div>
</div>

<div class="row" id="conference-package">

</div>
<div class="row">
    <div class="col-12 d-flex justify-content-center">
        <div class="d-flex flex-wrap py-2 mr-3" id="package-paginate">

        </div>
    </div>
</div>
<h5 class="mt-5 mb-5">Video Course</h5>
<div class="video-course">

</div>
<div class="row">
    <div class="col-12 d-flex justify-content-center">
        <div class="d-flex flex-wrap py-2 mr-3" id="video-paginate">

        </div>
    </div>
</div>
@include('student.new-package.modal')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.21/dist/js/splide.min.js"></script>
    @include('student.new-package.script')
@endpush
