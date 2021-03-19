@extends('cms.layouts.app')

@section('content')
<section>
    <div class="row">
        <div class="col-lg-4 col-12 p-5 order-2 order-lg-1">
            <h1>Get In Touch</h1>
            <p class="mt-3">Have a question or just want to get in touch? Have a question or just want to get in
            touch? Convey your message, we will get back to you soon.</p>
            <form id="form-question" method="POST" action="{{ url('question') }}" class="mt-4">
                <div class="form-group mb-4">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control mt-3" placeholder="Your Name">
                </div>
                <div class="form-group mb-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control mt-3" placeholder="Your Email">
                </div>
                <div class="form-group mb-4">
                    <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control mt-3" placeholder="Your Phone Number">
                </div>
                <div class="form-group mb-4">
                    <label>Message</label>
                    <textarea name="message" class="form-control mt-3" cols="30" rows="4" placeholder="Your Message"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Send Message</button>
            </form>
        </div>
        <div class="col-lg-8 col-12 p-5 order-1 order-lg-2">
            <h1>FAQ</h1>
            <h3 class="mt-2">Frequently Asked Questions</h3>
            <div class="panel-group mt-4" id="accordion" role="tablist" aria-multiselectable="true">
                @if($faqs)
                    @foreach($faqs as $faq)
                        <div class="panel panel-default mt-3">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion"
                                        href="#collapseOne{{ $faq->id }}" aria-expanded="true" aria-controls="collapseOne">
                                        <button class="btn btn-primary w-100 row-center-spacebetween rotate">{{ isset($faq->title) ? $faq->title:'' }}
                                            <img src="{{ asset('cms/assets/img/svg/Angle-down1.svg') }}" alt="">
                                        </button>
                                    </a> 
                                </h4>
                            </div>
                            <div id="collapseOne{{ $faq->id }}" class="panel-collapse collapse in" role="tabpanel"
                            aria-labelledby="headingOne">
                                <div class="panel-body p-4 mt-3">
                                    <p>{{ $faq->description ? $faq->description:''}}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
@include('cms.faq.script')
@endpush