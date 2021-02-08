@extends('layouts.app')

@section('content')

@include('admin.master.package.modal')
@endsection

@push('script')
    @include('admin.master.package.script')
@endpush
