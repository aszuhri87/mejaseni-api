@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="my-title">
                <h4 class="m-0 p-0">Transaction Coach</h4>
            </div>
            <div class="my-toolbar d-flex">
                <div class="form-group">
                    <div class="input-icon">
                        <select class="form-control" id="pageLength">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span>
                            <span class="svg-icon svg-icon-xxl svg-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
                                        <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="form-group ml-1">
                    <div class="input-icon">
                        <input type="text" class="form-control" id="search" placeholder="Search..." />
                        <span>
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                        <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"/>
                                    </g>
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="form-group ml-1">
                    <div class="btn-group dropleft">
                        <button class="btn btn-primary font-weight-bold dropdown-toggle py-3 px-5" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg">
                            <form class="p-5" id="form-filter">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" id="select-status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1">Waiting</option>
                                        <option value="2">Success</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Dari Tanggal</label>
                                    <input type="text" id="input-date-from" class="form-control datepicker" style="width: 100%" readonly placeholder="Select date">
                                </div>
                                <div class="form-group">
                                    <label>Sampai Tanggal</label>
                                    <input type="text" id="input-date-to" class="form-control datepicker" style="width: 100%" readonly placeholder="Select date">
                                </div>
                                <div class="d-flex">
                                    <button type="submit" id="btn-filter" style="width: 100%" class="btn btn-primary">Submit</button>
                                    <button type="reset" id="btn-reset" style="width: 100%" class="btn btn-secondary ml-1">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @can('reporting_transaction_coach_print')
                <div class="form-group ml-1">
                    <button type="button" id="btn-excel" class="btn btn-success py-3 px-5">Excel</button>
                </div>
                <div class="form-group ml-1">
                    <button type="button" id="btn-pdf" class="btn btn-danger py-3 px-5">PDF</button>
                </div>
                @endcan
            </div>
        </div>
        <table class="table table-separate table-head-custom mb-0 pb-0" width="100%" id="init-table">
            <thead>
                <tr>
                    <th width="">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="20%">Rekening</th>
                    <th width="20%">Nama Pemilik Rekening</th>
                    <th width="20%">Total</th>
                    <th width="15%">Status</th>
                    <th width="10%">Bukti</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <form id="form-export" method="POST">
            @csrf
            <input type="hidden" name="status">
            <input type="hidden" name="date_from">
            <input type="hidden" name="date_to">
        </form>
    </div>
</div>
@endsection

@push('script')
    @include('admin.report-transaction.coach.script')
@endpush
