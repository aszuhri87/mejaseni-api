@extends('layouts.app')

@section('content')
<div id="content">
    <div class="card">
        <div class="card-body d-flex justify-content-between">
            <div class="form-group d-flex">
                <div class="d-flex">
                    <label class="col-form-label text-right"><strong>Filter Sub Kategori Kelas</strong> </label>
                </div>
                <div class="d-flex ml-4" style="width: 250px !important">
                    <select name="filter" id="filter" class="form-control">
                        <option value="">Pilih Sub Kategori Kelas</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 video">

    </div>
</div>


{{-- @include('student.theory.modal') --}}
@endsection

@push('script')
    @include('student.video.script')
@endpush
