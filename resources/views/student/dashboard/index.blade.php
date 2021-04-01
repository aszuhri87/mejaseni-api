@extends('layouts.app')
@push('style')
<link rel="stylesheet" href="{{asset('assets/css/shepherd.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/tour.min.css')}}">
@endpush
@section('content')
<div style="display: none">
    <button id="tour">Tour</button>
</div>
<div class="row" style="margin-left: 0 !important">
    <div class="col-12" style="padding-left: 0 !important;">
        <div class="card" style="height: 540px !important">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div>
                            <h3 style="margin-bottom:0 !important">Summary Course</h3>
                            <span class="text-muted" id="text-summary">Lebih dari <span id="student-booking-month">0</span> siswa melakukan booking untuk bulan ini</span>
                        </div>
                    </div>
                    <div class="col-4 d-flex justify-content-end">
                        <span class="mr-5 d-flex align-items-center font-weight-bold">
                            <i class="label label-dot label-xl label-primary mr-2"></i>Kelas dihadiri</span>
                        <span class="d-flex align-items-center font-weight-bold">
                            <i class="label label-dot label-xl label-warning mr-2"></i>kelas dibooking</span>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <div id="chart">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mt-5">
    <div class="card-body">
        <div class="row">
            <div class="col-8">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-custom card-stretch gutter-b">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title font-weight-bolder">Progres Kelas</h3>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="row">
                                    <div class="col-5" style="border-right: 1px solid #a9a9a92c">
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="symbol symbol-40 symbol-light-danger mr-5">
                                                <span class="symbol-label">
                                                    <span class="svg-icon svg-icon-danger svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                            <path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000"/>
                                                        </g>
                                                    </svg></span>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column font-weight-bold">
                                                <a href="javascript:void(0)" class="text-dark text-hover-primary mb-1 font-size-lg" id="total-class"></a>
                                                <span class="text-muted">Total Kelas</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="symbol symbol-40 symbol-light-warning mr-5">
                                                <span class="symbol-label">
                                                    <span class="svg-icon svg-icon-warning svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Send.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M3,13.5 L19,12 L3,10.5 L3,3.7732928 C3,3.70255344 3.01501031,3.63261921 3.04403925,3.56811047 C3.15735832,3.3162903 3.45336217,3.20401298 3.70518234,3.31733205 L21.9867539,11.5440392 C22.098181,11.5941815 22.1873901,11.6833905 22.2375323,11.7948177 C22.3508514,12.0466378 22.2385741,12.3426417 21.9867539,12.4559608 L3.70518234,20.6826679 C3.64067359,20.7116969 3.57073936,20.7267072 3.5,20.7267072 C3.22385763,20.7267072 3,20.5028496 3,20.2267072 L3,13.5 Z" fill="#000000"/>
                                                        </g>
                                                    </svg></span>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column font-weight-bold">
                                                <a href="javascript:void(0)" class="text-dark text-hover-primary mb-1 font-size-lg" id="total-booking"></a>
                                                <span class="text-muted">Sesi Terbooking</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="symbol symbol-40 symbol-light-success mr-5">
                                                <span class="symbol-label">
                                                    <span class="svg-icon svg-icon-success svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Cupboard.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M3.5,3 L9.5,3 C10.3284271,3 11,3.67157288 11,4.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L3.5,20 C2.67157288,20 2,19.3284271 2,18.5 L2,4.5 C2,3.67157288 2.67157288,3 3.5,3 Z M9,9 C8.44771525,9 8,9.44771525 8,10 L8,12 C8,12.5522847 8.44771525,13 9,13 C9.55228475,13 10,12.5522847 10,12 L10,10 C10,9.44771525 9.55228475,9 9,9 Z" fill="#000000" opacity="0.3"/>
                                                            <path d="M14.5,3 L20.5,3 C21.3284271,3 22,3.67157288 22,4.5 L22,18.5 C22,19.3284271 21.3284271,20 20.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,4.5 C13,3.67157288 13.6715729,3 14.5,3 Z M20,9 C19.4477153,9 19,9.44771525 19,10 L19,12 C19,12.5522847 19.4477153,13 20,13 C20.5522847,13 21,12.5522847 21,12 L21,10 C21,9.44771525 20.5522847,9 20,9 Z" fill="#000000" transform="translate(17.500000, 11.500000) scale(-1, 1) translate(-17.500000, -11.500000) "/>
                                                        </g>
                                                    </svg></span>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column font-weight-bold">
                                                <a href="javascript:void(0)" class="text-dark text-hover-primary mb-1 font-size-lg" id="history-booking"></a>
                                                <span class="text-muted">Total Sesi Selesai</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="symbol symbol-40 symbol-light-info mr-5">
                                                <span class="symbol-label">
                                                    <span class="svg-icon svg-icon-info svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Media/Equalizer.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5"/>
                                                            <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5"/>
                                                            <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5"/>
                                                            <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5"/>
                                                        </g>
                                                    </svg></span>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column font-weight-bold">
                                                <a href="javascript:void(0)" class="text-dark text-hover-primary mb-1 font-size-lg" id="rest-session"></a>
                                                <span class="text-muted">Sisa Sesi</span>
                                            </div>
                                        </div>
                                        <div class="separator separator-dashed mt-8 mb-5"></div>
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="symbol symbol-40 symbol-light-primary mr-5">
                                                <span class="symbol-label">
                                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                                            <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                        </g>
                                                    </svg></span>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column font-weight-bold">
                                                <a href="javascript:void(0)" class="text-dark-75 text-hover-primary mb-1 font-size-lg" id="total-video"></a>
                                                <span class="text-muted">Video Tutorial</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="flex-grow-1" style="position: relative;">
                                            <div id="progress-class"></div>
                                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 321px; height: 252px;"></div></div><div class="contract-trigger"></div></div></div>
                                        <div class="pt-5">
                                            <p class="text-center"> <span id="percent-progress-class">0</span>% sesi dari <span id="class-active">0</span> kelas aktif yang Anda miliki telah Anda hadiri.</p>
                                            <a href="{{url('student/review')}}" class="btn btn-primary font-weight-bolder w-100 py-3">Lihat Review Kelas</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder">Upcomming Class</h3>
                    </div>
                    <div class="card-body pt-8 d-flex flex-column">
                        <div class="is_not_exist_upcoming">
                            <div style="height: 258.7px;" class="border rounded">
                                <div class="d-block text-center">
                                    <img src="{{asset('assets/images/not_exist_upcoming.png')}}" alt="" width="113px" height="131px" style="margin-top: 15% !important">
                                </div>
                                <div class="d-block text-center">
                                    <h5>Jadwal belum dibuat</h5>
                                </div>
                            </div>
                            <a href="{{url('student/schedule')}}" class="align-self-end btn btn-primary w-100 mt-5">Buat Jadwal</a>

                        </div>
                        <div class="is_exist_upcoming" style="display: none">
                            <img src="{{asset('assets/images/thumbnail.png')}}" class="rounded" height="250px" width="100%" id="img-upcoming">
                            <h3 class="mt-5" id="title-upcoming"></h3>
                            <div id="enter-master-lesson">

                            </div>
                            <span id="date-upcoming"></span>, <span id="time-upcoming"></span>
                            <div id="list-incoming">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-9">
                        <h3>My Course</h3>
                        <p id="text-course"></p>
                    </div>
                    <div class="col-3">
                        <select name="filter_course" id="filter-course" class="form-control">
                            <option value="0">All Course</option>
                            <option value="1">Classroom</option>
                            <option value="2">Video</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="overflow-auto p-5" id="my-course" style="height: 400px !important">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/js/shepherd.min.js') }}"></script>
    <script src="{{ asset('assets/js/tour.js') }}"></script>
    @include('student.dashboard.script')
@endpush
