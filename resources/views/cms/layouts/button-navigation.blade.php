@php
    $segment1 = Request::segment(1);
@endphp

<div class="bottom-navigation">
    <ul class="row mx-0">
        <li class="column-center @if($segment1 == null ){{'active-menu'}}@endif">
            <a class="column-center" href="{{ url('/') }}">
                <img src="{{ asset('cms/assets/img/svg/home.svg') }}" alt="">
            </a>
        </li>
        <li class="column-center @if($segment1 == 'class'){{'active-menu'}}@endif">
            <a class="column-center" href="{{ url('class') }}">
                <img src="{{ asset('cms/assets/img/svg/class.svg') }}" alt="">
            </a>
        </li>
        <li class="column-center @if($segment1 == 'video-course'){{'active-menu'}}@endif">
            <a class="column-center" href="{{ url('video-course') }}">
                <img src="{{ asset('cms/assets/img/svg/video-box.svg') }}" alt="">
            </a>
        </li>
        <li class="column-center @if($segment1 == 'store'){{'active-menu'}}@endif">
            <a class="column-center" href="{{ url('store') }}">
                <img src="{{ asset('cms/assets/img/svg/e-store.svg') }}" alt="">
            </a>
        </li>
        <li class="column-center @if($segment1 == 'news-event' || $segment1 == 'event-list'
      || $segment1 == 'event' || $segment1 == 'news' || $segment1 == 'news-list' ){{'active-menu'}}@endif">
            <a class="column-center" href="{{ url('news-event') }}">
                <img src="{{ asset('cms/assets/img/svg/event.svg') }}" alt="">
            </a>
        </li>
        @if (Auth::guard('student')->check())
        @php
            $str = substr(Auth::guard('student')->user()->image, 0, 4);

            if($str == 'http'){
                $image = Auth::guard('student')->user()->image;
            }else{
                $image = Storage::disk('s3')->url(Auth::guard('student')->user()->image);
            }
        @endphp
        <li class="column-center ">
            <a class="column-center" href="#">
                <img class="rounded-circle profile-img" id="profile-mobile"
                    src="{{ $image }}" alt="">
            </a>
        </li>
        @endif
    </ul>
</div>
