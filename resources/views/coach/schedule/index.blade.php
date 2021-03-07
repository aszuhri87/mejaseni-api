@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css"/>
@endpush

@section('content')

<div class="card">
    <div class="card-body">
        <div class="mt-10" id="calendar" style="height: 400px; border: none;"></div>
    </div>
</div>

@include('coach.schedule.modal')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.js"></script>
    @include('coach.schedule.script')
@endpush
