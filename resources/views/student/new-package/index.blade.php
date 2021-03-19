@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.21/dist/css/splide.min.css">
    <style>
        a:hover{
            color: #7F16A7 !important
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
<div class="row" id="category">
    {{-- <div class="col-2">
    </div> --}}
</div>
<h5 class="mt-5 mb-5">Class Conference Package</h5>
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
