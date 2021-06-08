@extends('layouts.app')
@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css" />
@endpush
@section('content')
<input type="hidden" value="" id="package_type">
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-5 text-left d-flex" style="padding-right: 0 !important">
                <div class="ml-5">
                    @if (preg_match("/https:/i", $data->image) > 0)
                        <img src="{{$data->image}}" width="100px" height="100px" class="rounded">
                    @elseif(preg_match("/https:/i", $data->image) == 0)
                        <img src="{{$data->image_url}}" width="100px" height="100px" class="rounded">
                    @else
                        <img src="{{asset('assets/images/ava-student.png')}}" width="100px" height="100px" class="rounded">
                    @endif
                </div>
                <div class="ml-5">
                    <p class="font-weight-bold font-size-h3" style="margin-bottom: 0 !important">{{$data->name}}</p>
                    <div class="row">
                        <div class="col-lg-6 d-flex mt-5" style="padding-right: 0 !important">
                            <div class="mr-5">
                                <span class="svg-icon svg-icon-primary svg-icon-3x">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Clothes/Crown.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24" />
                                            <path
                                                d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z"
                                                fill="#000000" opacity="0.3" />
                                            <path
                                                d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z"
                                                fill="#000000" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon--></span>
                            </div>
                            <div>
                                <p class="text-muted" style="margin-bottom: 0 !important">Kelas</p>
                                <p class="h2" id="total-class" style="margin-bottom: 0 !important"></p>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex mt-5" style="padding-right: 0 !important">
                            <div class="mr-5">
                                <span class="svg-icon svg-icon-primary svg-icon-3x">
                                    <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Media/Equalizer.svg-->
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16"
                                                rx="1.5"></rect>
                                            <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5"></rect>
                                            <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5"></rect>
                                            <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5"></rect>
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                            <div>
                                <p class="text-muted" style="margin-bottom: 0 !important">Rating</p>
                                <p class="h2" id="total-rating" style="margin-bottom: 0 !important"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <label>Pembelian Aktif</label>
                <div class="px-0">
                    <div class="class-owned__wrapper">
                        <div class="class-owned-selected">
                            <img id="class-image-selected" class="w-100" src="{{asset('assets/images/emptyclass-placeholder.svg')}}"
                                alt="">
                            <div class="h-100 class-owned-overlay">
                                <h5 id="class-name-selected">Belum Memiliki Kelas</h5>
                            </div>
                        </div>
                        <div class="class-owned vov  fastest f-30">
                            <ul style="padding-left:0 !important" id="list-class-active">

                            </ul>
                        </div>
                        <div class="see-all shadow row-center" style="display: none">
                            <a href="{{url('student/new-package')}}" class="text-white">
                                <span class="svg-icon svg-icon-md text-white mr-2"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z" fill="#000000"/>
                                    </g>
                                </svg></span>
                                Lihat Kelas
                            </a>
                        </div>
                        {{-- <div class="overlay"></div> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-2 text-center">
                <p>Jadwal Tersisa</p>
                <div>
                    <span class="display-3" id="last-meeting">0</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom mt-5">
    <div class="card-header card-header-tabs-line nav-tabs-line-3x">
        <div class="card-toolbar">
            <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                {{-- reguler class --}}
                <li class="nav-item mr-3">
                    <a class="nav-link active tab-regular" data-toggle="tab" href="#reguler-class">
                        <span class="nav-icon">
                            <span class="svg-icon">
                                <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Clothes/Crown.svg--><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z"
                                            fill="#000000" opacity="0.3" />
                                        <path
                                            d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z"
                                            fill="#000000" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon--></span>
                        </span>

                        <span class="nav-text font-size-lg">Regular Class</span>
                    </a>
                </li>
                {{-- end reguler class --}}

                {{-- special class --}}
                <li class="nav-item mr-3">
                    <a class="nav-link tab-special" data-toggle="tab" href="#special-class">
                        <span class="nav-icon">
                            <span class="svg-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path
                                            d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
                                            fill="#000000" opacity="0.3"></path>
                                        <path
                                            d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <span class="nav-text font-size-lg">Special Class</span>
                    </a>
                </li>
                {{-- end special class --}}

                {{-- master lesson class --}}
                <li class="nav-item mr-3">
                    <a class="nav-link tab-master-lesson" data-toggle="tab" href="#master-lesson-class">
                        <span class="nav-icon">
                            <span class="svg-icon">
                                <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Clothes/Crown.svg--><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z"
                                            fill="#000000" opacity="0.3" />
                                        <path
                                            d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z"
                                            fill="#000000" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon--></span>
                        </span>

                        <span class="nav-text font-size-lg">Master Lesson Class</span>
                    </a>
                </li>
                {{-- end master lesson class --}}

                {{-- change password --}}
                <li class="nav-item mr-3">
                    <a class="nav-link" data-toggle="tab" href="#coach-list">
                        <span class="nav-icon">
                            <span class="svg-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path
                                            d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
                                            fill="#000000" opacity="0.3"></path>
                                        <path
                                            d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <span class="nav-text font-size-lg">Coach List</span>
                    </a>
                </li>
                {{-- end change password --}}

            </ul>
        </div>
    </div>

    <div class="card-body">
        <form class="form" id="kt_form">
            <div class="tab-content">
                {{-- reguler class --}}
                <div class="tab-pane show active px-7" id="reguler-class" role="tabpanel">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="mt-5" id="calendar-regular"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end reguler class --}}

                {{-- special class --}}
                <div class="tab-pane show px-7" id="special-class" role="tabpanel">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="mt-5" id="calendar-special"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end special class --}}

                {{-- master lesson class --}}
                <div class="tab-pane show px-7" id="master-lesson-class" role="tabpanel">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="mt-5" id="calendar-master-lesson"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end master lesson class --}}

                {{-- change password form --}}
                <div class="tab-pane px-7" id="coach-list" role="tabpanel">
                    <div class="card-body">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Coach</th>
                                    <th scope="col">Phone Number</th>
                                    {{-- <th scope="col">Active Schedule</th> --}}
                                    <th scope="col">Rating</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">

                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- end change password form --}}
            </div>
        </form>
    </div>
</div>

@include('student.schedule.modal')
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.js"></script>
@include('student.schedule.script')
@endpush
