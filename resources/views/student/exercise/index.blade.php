@extends('layouts.app')

@section('content')
<div id="content">
    <div class="card">
        <div class="card-body d-flex justify-content-between">
            <div class="form-group d-flex">
                <div class="d-flex">
                    <label class="col-form-label text-right"><strong>Filter Kelas</strong> </label>
                </div>
                <div class="d-flex ml-4" style="width: 250px !important">
                    <select name="filter_class" id="filter-class" class="form-control">
                        <option value="">Pilih Kelas</option>
                    </select>
                </div>
            </div>
            <div class="form-group d-flex">
                <div>
                    <label class="col-form-label text-right"><strong>Range Picker</strong> </label>
                </div>
                <div class="ml-3" style="width: 80%">
                    <div class="input-daterange input-group" id="kt_datepicker_5">
                        <input type="text" class="form-control datepicker" name="date_start" id="date_start" placeholder="Date Start">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-ellipsis-h"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control datepicker" name="date_end" id="date_end" placeholder="Date End">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5 exercise">

    </div>
</div>


@include('student.exercise.modal')
@endsection

@push('script')
    @include('student.exercise.script')
@endpush
