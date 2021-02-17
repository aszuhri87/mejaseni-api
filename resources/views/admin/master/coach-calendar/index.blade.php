@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <img src="" alt="" width="150px" height="150px" class="rounded">
            </div>
        </div>
    </div>
</div>

@include('admin.master.coach-calendar.modal')
@endsection

@push('script')
    @include('admin.master.coach-calendar.script')
@endpush
