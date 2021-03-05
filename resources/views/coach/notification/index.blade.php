@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css" />
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
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table table-head-custom table-head-bg table-borderless table-vertical-center" id="init-table">
                    <thead>
                        <tr class="text-uppercase">
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Text</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <!--end::Table-->
        </div>
    </div>

    @include('coach.notification.modal')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.js"></script>
    @include('coach.notification.script')
@endpush
