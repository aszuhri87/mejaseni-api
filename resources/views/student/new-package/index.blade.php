@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.21/dist/css/splide.min.css">
    <style>
        a:hover{
            color: #7F16A7 !important
        }
        .class-category-filter__wrapper {
            border-radius: 8px;
            z-index: 1;
            overflow: hidden;
            border: 3px solid transparent;
            cursor: pointer;
            position: relative;
        }
        .class-category-selected {
            border: 3px solid #C41CD4;;
        }
        .class-category-selected .class-category-filter-overlay {
                background-color: #0000001e !important;
                color: #ffffff;
                font-weight: 500;
                font-size: 20px;
                transition: 0.3s;
        }
        .class-category-filter__wrapper .class-category-filter-overlay {
            width: 100%;
            height: 100%;
            background-color: #00000069;
            position: absolute;
            top: 0;
            color: #ffffff;
            transition: 0.3s;
        }

        .class-category-filter__wrapper img {
            min-width: 100%;
            height: 82px;
            object-fit: cover;
        }

        .class-category-filter__wrapper iframe {
            z-index: -1;
        }

        .row-center {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
            align-content: center;
        }
    </style>
@endpush
@section('content')
<div class="splide mb-4" id="category-splide">
    <div class="splide__track">
        <ul class="splide__list">
            <li class="splide__slide px-2">
                <div class="class-category-filter__wrapper class-category-selected">
                    <div class="class-category-filter-overlay row-center">
                        <h4>Musik</h4>
                    </div>
                    <img src="././assets/images/category-placeholder.png" alt="">
                </div>
            </li>
            <li class="splide__slide px-2">
                <div class="class-category-filter__wrapper">
                    <div class="class-category-filter-overlay row-center">
                        <h4>Suling</h4>
                    </div>
                    <img src="././assets/images/category-placeholder.png" alt="">
                </div>
            </li>
            <li class="splide__slide px-2">
                <div class="class-category-filter__wrapper">
                    <div class="class-category-filter-overlay row-center">
                        <h4>Piano</h4>
                    </div>
                    <img src="././assets/images/category-placeholder.png" alt="">
                </div>
            </li>
            <li class="splide__slide px-2">
                <div class="class-category-filter__wrapper">
                    <div class="class-category-filter-overlay row-center">
                        <h4>Suling</h4>
                    </div>
                    <img src="././assets/images/category-placeholder.png" alt="">
                </div>
            </li>
            <li class="splide__slide px-2">
                <div class="class-category-filter__wrapper">
                    <div class="class-category-filter-overlay row-center">
                        <h4>Piano</h4>
                    </div>
                    <img src="././assets/images/category-placeholder.png" alt="">
                </div>
            </li>
        </ul>
    </div>
</div>
<div id="category" class="d-flex">

</div>
<h5 class="mt-5 mb-5">Class Conference Package</h5>
<div class="row" id="conference-package">

</div>
<h5 class="mt-5 mb-5">Video Course</h5>
<div class="video-course">


</div>
@include('student.new-package.modal')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.21/dist/js/splide.min.js"></script>
    <script>
        new Splide('#category-splide', {
            type: 'loop',
            arrow: false,
            perPage: 5,
            perMove: 2,
            pagination: false,
            padding: {
                right: '0rem',
                left: '0rem',
            },
            breakpoints: {
                1025: {
                    perPage: 3,
                    padding: {
                        right: '0rem',
                        left: '0rem',
                    },
                },
                991: {
                    perPage: 2,
                    padding: {
                        right: '0rem',
                        left: '0rem',
                    },
                },
                640: {
                    perPage: 1,
                    padding: {
                        right: '3rem',
                        left: '3rem',
                    },
                },
            }
        }).mount();
    </script>
    @include('student.new-package.script')
@endpush
