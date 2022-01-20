@extends('layouts.app')

@push('style')
<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="my-title">
                <h4 class="m-0 p-0">Privacy Policy</h4>
            </div>
        </div>
        <table class="table table-separate table-head-custom mb-0 pb-0" width="100%" id="init-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th width="90%">Description</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@include('admin.cms.privacy-policy.modal')
@endsection

@push('script')
    <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
    @include('admin.cms.privacy-policy.script')
@endpush
