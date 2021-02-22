@extends('layouts.app')

@section('content')
<div id="category" class="d-flex">

</div>
<h5 class="mt-5 mb-5">Class Conference Package</h5>
<div class="row" id="conference-package">

</div>
<h5 class="mt-5 mb-5">Video Course</h5>
<div class="video-course">

    
</div>
@include('student.new-package.modal')
@endsection

@push('script')
    @include('student.new-package.script')
@endpush
