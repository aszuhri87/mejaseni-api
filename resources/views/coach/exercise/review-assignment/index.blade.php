@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');


        .star-widget input {
            display: none;
        }

        .star-widget label {
            font-size: 40px;
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

<div class="row" id="filter-place">
    <div class="col-md-5 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <h6 class="m-0 p-0">Filter Materi</h6>
                </div>
                <div class="form-group">
                    <label>
                        Kelas
                        <span class="text-danger">*</span>
                    </label>
                    <select name="classroom_coach_id" id="classroom"></select>
                </div>
                <div class="form-group">
                    <label>
                        Sesi
                        <span class="text-danger">*</span>
                    </label>
                    <select name="session_coach_id" class="form-control" id="session"></select>
                </div>
                <div class="form-group">
                    <button type="button" id="show-table"
                        class="btn btn-primary btn-icon w-auto px-2 waves-effect width-md waves-light text-center btn-loading-basic">
                        Tampilkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <!--begin::Advance Table Widget 4-->
    <div class="card card-custom gutter-b  mt-5" id="card-review-assignment" style="display: none">
        <!--begin::Header-->
        <div class="card-header border-0 py-5">
            <h3 class="card-title d-flex align-items-center">
                <button class="btn btn-outline-danger mr-3" id="back-btn">Kembali</button>
                <span class="card-label font-weight-bolder text-dark">Review</span>
            </h3>
            <div class="card-toolbar">
                <div class="form-group row pr-5">
                    <label class="col-form-label text-right col-lg-3 col-sm-12">Filter Tanggal</label>
                    <div class="col-lg-9 col-md-9 col-sm-12">
                        <div class="input-daterange input-group" id="filter_date">
                            <input type="text" class="form-control" name="start" id="start_date" />
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                            </div>
                            <input type="text" class="form-control" name="end" id="end_date" />
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
            <div class="tab-content">
                <!--begin::Table-->
                <div class="table-responsive">
                    <table class="table table-head-custom table-head-bg table-borderless table-vertical-center" width="100%"
                        id="init-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th width="20%">Tanggal Pengumpulan</th>
                                <th width="20%">Tanggal Penugasan</th>
                                <th width="20%">Siswa</th>
                                <th width="20%">Status</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!--end::Table-->
            </div>
        </div>
        <!--end::Body-->
    </div>

    @include('coach.exercise.review-assignment.modal')
@endsection

@push('script')
    @include('coach.exercise.review-assignment.script')
@endpush
