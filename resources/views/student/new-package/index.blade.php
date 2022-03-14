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
        .gmap {
            width: 100%;
            height: 300px;
        }
        .controls {
            background-color: #fff;
            border-radius: 2px;
            border: 1px solid transparent;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            box-sizing: border-box;
            font-family: 'Roboto';
            font-size: 15px;
            font-weight: 300;
            height: 29px;
            margin-left: 17px;
            margin-top: 10px;
            outline: none;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 400px;
        }

        .controls:focus {
            border-color: #4d90fe;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: 'Roboto';
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: 'Roboto';
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-family: 'Roboto';
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 400px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        @media only screen and (max-width : 768px) {
            #pac-input {
                position: absolute;
                left: 0 !important;
                top: 50px !important;
                width: 50%;
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
        <h5 class="mt-5 mb-5" id="init-class">Class Conference Package</h5>
    </div>
    <div class="col-lg-6 d-flex justify-content-end mb-5" >
        <input type="hidden" name="init_class_sub_category" id="init_class_sub_category">
        <input type="hidden" name="init_class_category" id="init_class_category">
        <div class="btn-group" id="group-filter" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-outline-primary filter btn-filter" data-package_type='2' id="filter-2">Regular</button>
            <button type="button" class="btn btn-outline-primary filter btn-filter" data-package_type='1' id="filter-1">Special</button>
            <button type="button" class="btn btn-outline-primary master-lesson btn-filter" data-package_type='3' id="filter-3">Master Lesson</button>
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
<h5 class="mt-5 mb-5">Tutorial Video</h5>
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
