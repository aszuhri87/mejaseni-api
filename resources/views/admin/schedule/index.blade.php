@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css"/>
@endpush

@section('content')

<div class="card d-none">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="my-title">
                <h6 class="m-0 p-0">Filter Category</h6>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        {{-- <div class="d-flex justify-content-between align-items-center">
            <div style="width: 45%">
                <div class="separator separator-solid"></div>
            </div>
            <button class="btn btn-outline-primary btn-sm" style="border-radius: 10px">View List</button>
            <div style="width: 45%">
                <div class="separator separator-solid"></div>
            </div>
        </div> --}}
        <div class="mt-10" id="calendar" style="height: 400px; border: none;"></div>
    </div>
</div>

@include('admin.schedule.modal')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.js"></script>
    @include('admin.schedule.script')
@endpush
