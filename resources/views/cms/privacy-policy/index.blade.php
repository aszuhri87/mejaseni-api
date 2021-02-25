@extends('cms.layouts.app')

@section('content')
<section>
    <div class="row">
        <div class="col-md-12 p-5">
            <h1>Kebijakan Privasi</h1>
            <h3 class="mt-2">Privacy Policy</h3>
            <p class="mt-3">{{ $privacy_policy->description ? $privacy_policy->description:'' }}</p>


            <div class="panel-group mt-5" id="accordion" role="tablist" aria-multiselectable="true">
                @foreach($privacy_policy_items as $privacy_policy_item)
                    <div class="panel panel-default mt-3">
                        <div class="panel-heading" role="tab" id="heading{{ $loop->index}}">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $loop->index }}" aria-expanded="true" aria-controls="collapse{{ $loop->index }}">
                                    <button class="btn btn-primary w-100 row-center-spacebetween rotate">
                                        {{ $privacy_policy_item->title ? $privacy_policy_item->title:'' }}
                                        <img src="{{ asset('cms/assets/img/svg/Angle-down1.svg') }}" alt="">
                                    </button>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse{{ $loop->index }}" class="panel-collapse collapse in" role="tabpanel"
                        aria-labelledby="heading{{ $loop->index}}">
                        <div class="panel-body p-4 mt-3">
                            <p>{{ $privacy_policy_item->description ? $privacy_policy_item->description:'' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
        </div>
    </div>
</div>
</section>
@endsection
@push('script')
@include('cms.privacy-policy.script')
@endpush