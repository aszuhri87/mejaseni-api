@extends('layouts.app')

@section('content')
<div id="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group row">
                        <label class="col-form-label text-right col-lg-4 col-sm-12"><strong>Filter Kelas</strong> </label>
                        <div class="col-lg-8 col-sm-12">
                            <select name="filter_class" id="filter-class" class="form-control">
                                <option value="">Pilih Kelas</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group row">
                        <label class="col-form-label text-right col-lg-4 col-sm-12"><strong>Range Picker</strong> </label>
                        <div class="col-lg-8 col-sm-12">
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
                {{-- <div class="col-lg-2">
                    <select name="filter_class" id="" class="form-control">
                        <option value="">Semua Materi   </option>
                    </select>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="row mt-5 theory">

    </div>
</div>


{{-- @include('student.theory.modal') --}}
@endsection

@push('script')
    @include('student.theory.script')
@endpush
