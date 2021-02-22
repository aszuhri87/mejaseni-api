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
        </div>
    </div>

    <div class="row mt-5 video">

    </div>
</div>


{{-- @include('student.theory.modal') --}}
@endsection

@push('script')
    @include('student.video.script')
@endpush
