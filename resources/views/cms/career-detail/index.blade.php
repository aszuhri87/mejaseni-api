@extends('cms.layouts.app')

@push('style')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
@endpush

@section('content')
<section id="career-detail__wrapper">
    <div class="row">
        <div class="col-lg-3 mt-lg-0 mt-3">
            <div class="bg-white career-detail__aside column-center py-lg-5 py-2">
                <img class="mt-5" src="{{ asset('cms/assets/img/svg/Tie1.svg') }}" alt="">
                <h3 class="mt-5">{{ $career->title ? $career->title:'' }}</h3>
                <p class="row-center-start mt-3 mb-5"><img class="mr-2"
                        src="{{ asset('cms/assets/img/svg/marker.svg') }}"
                        alt="">{{ $career->placement ? $career->placement:'' }}</p>
                <a data-toggle="modal" data-target="#modal-apply-career"
                    class="btn btn-primary mt-5 mb-4 w-25">Apply</a>
            </div>
        </div>
        <div class="col-lg-9 mb-lg-0 mb-3">
            <div class="bg-white career-detail p-5">
                <div class="p-0 p-md-2">
                    @if($job_descriptions)
                    <h4>Job Description</h4>
                    <ul class="mt-3 mb-4 pl-4 pl-md-5">
                        @foreach($job_descriptions as $job_description)
                        <li>{{ $job_description->description ? $job_description->description:'' }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if($job_requirements)
                    <h4 class="mt-5">Requirements</h4>
                    <ul class="mt-3 pl-5">
                        @foreach($job_requirements as $job_requirement)
                        <li>{{ $job_requirement->description ? $job_requirement->description:'' }}</li>
                        @endforeach
                    </ul>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>
@include('cms.career-detail.modal')
@endsection

@push('script')

<!-- include FilePond library -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

<!-- include FilePond plugins -->
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

<!-- include FilePond jQuery adapter -->
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

@include('cms.career-detail.script')
@endpush
