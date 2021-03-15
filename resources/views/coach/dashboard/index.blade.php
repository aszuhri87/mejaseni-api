@extends('layouts.app')

@push('style')
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.35.0/skin-win8/ui.fancytree.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');


        .star-widget input {
            display: none;
        }

        .star-widget label {
            font-size: 25px;
            color: #d7d8df;
            padding: 10px;
            float: right;
            transition: all 0.2s ease;
        }

        input:not(:checked)~label:hover,
        input:not(:checked)~label:hover~label {
            color: #FFA800;
        }

        input:checked~label {
            color: #FFA800;
        }

        input#rate-5:checked~label {
            color: #FFA800;
            text-shadow: 0 0 20px #952;
        }

    </style>
@endpush

@section('content')
    <div class="row">
        <!--begin::Summary Course-->
        <div class="col-xl-12">
            <div class="card card-custom card-stretch gutter-b">
                <!--begin::Header-->
                <div class="card-header h-auto border-0">
                    <div class="card-title py-5">
                        <h3 class="card-label">
                            <span class="d-block text-dark font-weight-bolder">Summary Course</span>
                            <span class="d-block text-muted mt-2 font-size-sm">
                                <span class="total-booking"></span> siswa melakukan booking untuk minggu ini
                            </span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <span class="mr-5 d-flex align-items-center font-weight-bold">
                            <i class="label label-dot label-xl label-primary mr-2"></i>Kelas dihadiri</span>
                        <span class="d-flex align-items-center font-weight-bold">
                            <i class="label label-dot label-xl label-warning mr-2"></i>Kelas dibooking</span>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div id="summary_course_chart">
                                <div class="spinner spinner-primary spinner-lg spinner-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
        </div>
        <div class="col-xl-8">
            <!--begin::Mixed Widget 17-->
            <div class="card card-custom gutter-b card-stretch">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <div class="card-title font-weight-bolder">
                        <div class="card-label">Summary Course
                        <div class="font-size-sm text-muted mt-2"><span class="total-booking"></span> siswa melakukan booking untuk minggu ini</div></div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body p-0 d-flex flex-column">
                    <!--begin::Items-->
                    <div class="flex-grow-1 card-spacer">
                        <div class="row row-paddingless mt-5 mb-10">
                            <!--begin::Item-->
                            <div class="col">
                                <div class="d-flex align-items-center mr-2">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-45 symbol-light-danger mr-4 flex-shrink-0">
                                        <div class="symbol-label">
                                            <span class="svg-icon svg-icon-md svg-icon-danger">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Cart3.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                    viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path
                                                            d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"
                                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                        <path
                                                            d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"
                                                            fill="#000000" />
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Title-->
                                    <div>
                                        <div class="font-size-h4 text-dark-75 font-weight-bolder total-kelas">0</div>
                                        <div class="font-size-sm text-muted font-weight-bold mt-1">Total Kelas</div>
                                    </div>
                                    <!--end::Title-->
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="col">
                                <div class="d-flex align-items-center mr-2">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-45 symbol-light-info mr-4 flex-shrink-0">
                                        <div class="symbol-label">
                                            <span class="svg-icon svg-icon-md svg-icon-info">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                    viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path
                                                            d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z"
                                                            fill="#000000" />
                                                        <rect fill="#000000" opacity="0.3"
                                                            transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)"
                                                            x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Title-->
                                    <div>
                                        <div class="font-size-h4 text-dark-75 font-weight-bolder video-tutorial">0</div>
                                        <div class="font-size-sm text-muted font-weight-bold mt-1">Video Tutorial</div>
                                    </div>
                                    <!--end::Title-->
                                </div>
                            </div>
                            <!--end::Widget Item-->
                        </div>
                        <div class="row row-paddingless mt-5 mb-10">
                            <!--begin::Item-->
                            <div class="col">
                                <div class="d-flex align-items-center mr-2">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-45 symbol-light-warning mr-4 flex-shrink-0">
                                        <div class="symbol-label">
                                            <span class="svg-icon svg-icon-md svg-icon-warning">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flip-vertical.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                    viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path
                                                            d="M9.07117914,12.5710461 L13.8326627,12.5710461 C14.108805,12.5710461 14.3326627,12.3471885 14.3326627,12.0710461 L14.3326627,0.16733734 C14.3326627,-0.108805035 14.108805,-0.33266266 13.8326627,-0.33266266 C13.6282104,-0.33266266 13.444356,-0.208187188 13.3684243,-0.0183579985 L8.6069408,11.8853508 C8.50438409,12.1417426 8.62909204,12.4327278 8.8854838,12.5352845 C8.94454394,12.5589085 9.00756943,12.5710461 9.07117914,12.5710461 Z"
                                                            fill="#000000" opacity="0.3"
                                                            transform="translate(11.451854, 6.119192) rotate(-270.000000) translate(-11.451854, -6.119192)" />
                                                        <path
                                                            d="M9.23851648,24.5 L14,24.5 C14.2761424,24.5 14.5,24.2761424 14.5,24 L14.5,12.0962912 C14.5,11.8201488 14.2761424,11.5962912 14,11.5962912 C13.7955477,11.5962912 13.6116933,11.7207667 13.5357617,11.9105959 L8.77427814,23.8143047 C8.67172143,24.0706964 8.79642938,24.3616816 9.05282114,24.4642383 C9.11188128,24.4878624 9.17490677,24.5 9.23851648,24.5 Z"
                                                            fill="#000000"
                                                            transform="translate(11.500000, 18.000000) scale(1, -1) rotate(-270.000000) translate(-11.500000, -18.000000)" />
                                                        <rect fill="#000000" opacity="0.3"
                                                            transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000)"
                                                            x="11" y="2" width="2" height="20" rx="1" />
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Title-->
                                    <div>
                                        <div class="font-size-h4 text-dark-75 font-weight-bolder booking-saat-ini">0</div>
                                        <div class="font-size-sm text-muted font-weight-bold mt-1">Booking Saat Ini</div>
                                    </div>
                                    <!--end::Title-->
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="col">
                                <div class="d-flex align-items-center mr-2">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-45 symbol-light-success mr-4 flex-shrink-0">
                                        <div class="symbol-label">
                                            <span class="svg-icon svg-icon-md svg-icon-success">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Cupboard.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                    viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path
                                                            d="M3.5,3 L9.5,3 C10.3284271,3 11,3.67157288 11,4.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L3.5,20 C2.67157288,20 2,19.3284271 2,18.5 L2,4.5 C2,3.67157288 2.67157288,3 3.5,3 Z M9,9 C8.44771525,9 8,9.44771525 8,10 L8,12 C8,12.5522847 8.44771525,13 9,13 C9.55228475,13 10,12.5522847 10,12 L10,10 C10,9.44771525 9.55228475,9 9,9 Z"
                                                            fill="#000000" opacity="0.3" />
                                                        <path
                                                            d="M14.5,3 L20.5,3 C21.3284271,3 22,3.67157288 22,4.5 L22,18.5 C22,19.3284271 21.3284271,20 20.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,4.5 C13,3.67157288 13.6715729,3 14.5,3 Z M20,9 C19.4477153,9 19,9.44771525 19,10 L19,12 C19,12.5522847 19.4477153,13 20,13 C20.5522847,13 21,12.5522847 21,12 L21,10 C21,9.44771525 20.5522847,9 20,9 Z"
                                                            fill="#000000"
                                                            transform="translate(17.500000, 11.500000) scale(-1, 1) translate(-17.500000, -11.500000)" />
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Title-->
                                    <div>
                                        <div class="font-size-h4 text-dark-75 font-weight-bolder riwayat-booking">0</div>
                                        <div class="font-size-sm text-muted font-weight-bold mt-1">Riwayat Booking</div>
                                    </div>
                                    <!--end::Title-->
                                </div>
                            </div>
                            <!--end::Item-->
                        </div>
                        <div class="row row-paddingless">
                            <!--begin::Item-->
                            <div class="col">
                                <div class="d-flex align-items-center mr-2">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-45 symbol-light-primary mr-4 flex-shrink-0">
                                        <div class="symbol-label">
                                            <span class="svg-icon svg-icon-md svg-icon-primary">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                    viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <rect fill="#000000" opacity="0.3" x="13" y="4" width="3"
                                                            height="16" rx="1.5" />
                                                        <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5" />
                                                        <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5" />
                                                        <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5" />
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Title-->
                                    <div>
                                        <div class="font-size-h4 text-dark-75 font-weight-bolder tidak-hadir">0</div>
                                        <div class="font-size-sm text-muted font-weight-bold mt-1">Tidak Hadir</div>
                                    </div>
                                    <!--end::Title-->
                                </div>
                            </div>
                            <!--end::Item-->
                        </div>
                    </div>
                    <!--end::Items-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 17-->
        </div>
        <!--begin::Salary-->
        <div class="col-xl-4">
            <!--begin::Tiles Widget 8-->
            <div class="card card-custom gutter-b card-stretch">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <div class="card-title">
                        <div class="card-label">
                            <div class="font-weight-bolder">Salary</div>
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-clean btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ki ki-bold-more-hor"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <!--begin::Navigation-->
                                <ul class="navi navi-hover">
                                    <li class="navi-item">
                                        <a href="{{url('coach/withdraw/detail')}}" class="navi-link">
                                            <span class="navi-text">Lihat Riwayat</span>
                                        </a>
                                    </li>
                                </ul>
                                <!--end::Navigation-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-column p-0">
                    <!--begin::Items-->
                    <div class="flex-grow-1 card-spacer">
                        <!--begin::Item-->
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <div class="d-flex align-items-center mr-2">
                                <div>
                                    <div class="font-size-sm text-muted font-weight-bold mt-1">Balance
                                    </div>
                                    <div class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder d-balance">Rp. 0</div>
                                </div>
                            </div>
                            <a href="#" class="btn btn-light-info font-weight-bold mr-2 btn-withdraw">
                                Withdraw
                                <span class="svg-icon svg-icon-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                            <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                            <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                        </g>
                                    </svg>
                                </span>
                            </a>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center mr-2">
                                <div>
                                    <div class="font-size-sm text-muted font-weight-bold mt-1">Total Income</div>
                                    <div class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder d-amount">Rp. 0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--begin::Chart-->
                    <div id="incomes-chart" class="card-rounded-bottom" data-color="danger" style="height: 100px">
                        <div class="spinner spinner-primary spinner-lg spinner-center"></div>
                    </div>
                    <!--end::Chart-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Tiles Widget 8-->
        </div>
        <!--end::Salary-->

        <!--end::closest schedule-->
        <div class="col-lg-6">
            <!--begin::List Widget 3-->
            <div class="card card-custom card-stretch card-shadowless gutter-b">
                <!--begin::Header-->
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bolder text-dark">Closest Schedule</h3>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-2 closest-schedule">
                    <div class="spinner spinner-primary spinner-lg spinner-center"></div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::List Widget 3-->
        </div>
        <!--end::closest schedule-->

        <!--begin::latest complate class-->
        <div class="col-lg-6">
            <!--begin::List Widget 3-->
            <div class="card card-custom card-stretch card-shadowless gutter-b">
                <!--begin::Header-->
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bolder text-dark">Latest Complete Class</h3>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-2 latest-complete-class">
                    <div class="spinner spinner-primary spinner-lg spinner-center"></div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::List Widget 3-->
        </div>

        <!--end::latest complate class-->

        <!--begin::last class 3-->
        <div class="col-lg-12">
            <!--begin::Las-->
            <div class="card card-custom gutter-b">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">Last Class</span>
                    </h3>
                    <div class="card-toolbar">
                        <div class="form-group row pr-5">
                            <label class="col-form-label text-right col-lg-3 col-sm-12">Filter Tanggal</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <div class="input-daterange input-group" id="filter_date">
                                    <input type="text" class="form-control datepicker" name="start" id="start_date" autocomplete="off" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="end" id="end_date" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <input type="text" name="search" id="search" class="form-control"
                                    placeholder="Masukan kata kunci" />
                                <span>
                                    <i class="flaticon2-search-1 icon-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-0 pb-3">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table table-head-custom table-head-bg table-borderless table-vertical-center"
                            id="last-class-table">
                            <thead>
                                <tr class="text-uppercase">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kelas</th>
                                    <th>Tipe Kelas</th>
                                    <th>Siswa</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Advance Table Widget 3-->
        </div>
        <!--end::last class 3-->


    </div>
    @include('coach.dashboard.modal')

@endsection

@push('script')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.35.0/jquery.fancytree-all.min.js"></script>
    @include('coach.dashboard.script')
@endpush
