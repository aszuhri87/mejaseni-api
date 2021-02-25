@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="my-title">
                <h4 class="m-0 p-0">Company</h4>
            </div>
        </div>
        <table class="table table-separate table-head-custom mb-0 pb-0" width="100%" id="init-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th width="25%">Name</th>
                    <th width="15%">Email</th>
                    <th width="15%">Telephone</th>
                    <th width="35%">Address</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@include('admin.cms.company.modal')
@endsection

@push('script')
    @include('admin.cms.company.script')
@endpush
