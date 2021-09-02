@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css"/>
    <style>
        .bg-orange{
            background-color: #ffa962
        }

        .border-orange{
            border: 1px solid #ffa962
        }

        .bg-green{
            background-color: #6cd87e
        }

        .border-green{
            border: 1px solid #6cd87e
        }
    </style>
@endpush

@section('content')

<div class="card">
    <a href="{{ url('/coach/schedule-print')}}" class="btn btn-primary mt-3 ml-3" style="width: 150px" target="_blank">Print Schedule</a>
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
