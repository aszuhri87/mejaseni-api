@extends('layouts.app')
@push('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/css/star-rating.min.css" media="all"
    rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/themes/krajee-svg/theme.css" media="all"
    rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vaibhav111tandon/vov.css@latest/vov.css">
@endpush
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-5 text-left d-flex" style="padding-right: 0 !important">
                <div>
                    @if (preg_match("/https:/i", $data->image) > 0)
                        <img src="{{$data->image}}" width="150px" height="150px" class="rounded">
                    @elseif(preg_match("/https:/i", $data->image) == 0)
                        <img src="{{$data->image_url}}" width="150px" height="150px" class="rounded">
                    @else
                        <img src="{{asset('assets/images/ava-student.png')}}" width="150px" height="150px" class="rounded">
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
                    <a href="{{url('student/schedule')}}" class="btn btn-primary mt-5">Lihat Schedule</a>
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
                        <div class="see-all shadow row-center">
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
                <p>Sisa Pertemuan</p>
                <div>
                    <span class="display-3" id="last-meeting">0</span>
                </div>
                <div id="rating-class" class="mt-5" style="display: none">
                    <button type="button" class="btn btn-sm btn-primary btn-rating-class">Beri Rating</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mt-5">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="my-title">
                <h4 class="m-0 p-0">Booking Class</h4>
            </div>
            <div class="my-toolbar d-flex">
                <div class="form-group">
                    <div class="input-icon">
                        <select class="form-control" id="pageLength">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span>
                            <span class="svg-icon svg-icon-xxl svg-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
                                        <path
                                            d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="form-group ml-1">
                    <div class="input-icon">
                        <input type="text" class="form-control" id="search" placeholder="Search..." />
                        <span>
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-separate table-head-custom mb-0 pb-0" width="100%" id="init-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th width="20%">Tanggal</th>
                    <th width="15%">Waktu</th>
                    <th width="20%">Kelas</th>
                    <th width="20%">Coach</th>
                    <th width="10%">Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-5">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="my-title">
                <h4 class="m-0 p-0">Last Class</h4>
            </div>
            <div class="my-toolbar d-flex">
                <div class="form-group">
                    <div class="input-icon">
                        <select class="form-control" id="pageLength2">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span>
                            <span class="svg-icon svg-icon-xxl svg-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
                                        <path
                                            d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="form-group ml-1">
                    <div class="input-icon">
                        <input type="text" class="form-control" id="search2" placeholder="Search..." />
                        <span>
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-separate table-head-custom mb-0 pb-0" width="100%" id="init-table2">
            <thead>
                <tr>
                    <th>No</th>
                    <th width="20%">Tanggal</th>
                    <th width="15%">Waktu</th>
                    <th width="20%">Kelas</th>
                    <th width="20%">Coach</th>
                    <th width="10%">Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@include('student.my-class.modal')
@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/js/star-rating.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.6/themes/krajee-svg/theme.js"></script>
<script>
    // $("#class-owned1").click(function () {
    //     $(".class-owned").removeClass("fade-in-down");
    //     $(".class-owned").addClass("fade-out-up");
    //     $(".class-owned").css("display", "none");
    //     $("#class-name-selected").html("Basic Piano");
    //     $("#class-image-selected").attr("src", "././assets/img/master-lesson__banner2.jpg");
    // });
  </script>
@include('student.my-class.script')
@endpush
