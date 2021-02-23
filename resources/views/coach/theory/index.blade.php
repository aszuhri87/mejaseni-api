@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css" />
@endpush

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-beetween row">
                <div class="col-sm-8">
                    <div class="row text-left">
                        <div class="col-md-2 col-sm-2">
                            <div class="form-group">
                                <h6 class="m-0 p-0">Filter Category</h6>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 select-classroom-coach">
                            <div class="form-group">
                                <select name="classroom_coach_id" id="classroom_coach"></select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 select-session-coach">
                            <div class="form-group">
                                <select name="session_coach_id" class="form-control" id="session_coach"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row text-right">                        
                        <div class="col-md-12 col-sm-12 select-session-coach">
                            <div class="form-group">
                                <button type="button" id="show-btn"
                                    class="btn btn-primary btn-icon w-auto px-2 waves-effect width-md waves-light ml-1 text-center btn-loading-basic">
                                    Tampilkan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5" id="card-theory" style="display: none" >
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="my-title"></div>
                    <button type="button" id="add-btn"
                        class="btn btn-primary btn-icon w-auto px-2 waves-effect width-md waves-light ml-1">
                        <span class="svg-icon svg-icon-white svg-icon-2x">
                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Files/Upload.svg--><svg
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path
                                        d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z"
                                        fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                    <rect fill="#000000" opacity="0.3" x="11" y="2" width="2" height="14" rx="1" />
                                    <path
                                        d="M12.0362375,3.37797611 L7.70710678,7.70710678 C7.31658249,8.09763107 6.68341751,8.09763107 6.29289322,7.70710678 C5.90236893,7.31658249 5.90236893,6.68341751 6.29289322,6.29289322 L11.2928932,1.29289322 C11.6689749,0.916811528 12.2736364,0.900910387 12.6689647,1.25670585 L17.6689647,5.75670585 C18.0794748,6.12616487 18.1127532,6.75845471 17.7432941,7.16896473 C17.3738351,7.57947475 16.7415453,7.61275317 16.3310353,7.24329415 L12.0362375,3.37797611 Z"
                                        fill="#000000" fill-rule="nonzero" />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        Upload
                    </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row" id="theory-list">
                
            </div>

        </div>
    </div>

    @include('coach.theory.modal')
@endsection

@push('script')
    @include('coach.theory.script')
@endpush
