@if(Auth::guard('student')->check())
<div class="bottom-navigation">
    <ul class="row mx-0">
        <li class="column-center active-menu">
            <a class="column-center" href="{{ url('/') }}">
                <img src="{{ asset('cms/assets/img/svg/home.svg') }}" alt="">Home
            </a>
        </li>
        <li class="column-center">
            <a class="column-center" href="{{ url('class') }}">
                <img src="{{ asset('cms/assets/img/svg/class.svg') }}" alt="">Kelas
            </a>
        </li>
        <li class="column-center">
            <a class="column-center" href="{{ url('store') }}">
                <img src="{{ asset('cms/assets/img/svg/e-store.svg') }}" alt="">E-Store
            </a>
        </li>
        <li class="column-center ">
            <a class="column-center" href="{{ url('news-event') }}">
                <img src="{{ asset('cms/assets/img/svg/event.svg') }}" alt="">Event
            </a>
        </li>
        <li class="column-center ">
            <a class="column-center" href="#">
                <img class="rounded-circle profile-img" id="profile-mobile"
                    src="{{ asset('cms/assets/img/coach.png') }}" alt="">
            </a>
        </li>
    </ul>
</div>
@else
<div class="bottom-navigation">
    <ul class="row mx-0">
        <li class="column-center active-menu">
            <a class="column-center" href="{{ url('/login') }}">
                <img src="{{ asset('cms/assets/img/svg/home.svg') }}" alt="">Login
            </a>
        </li>
        <li class="column-center">
            <a class="column-center" href="{{ url('register') }}">
                <img src="{{ asset('cms/assets/img/svg/class.svg') }}" alt="">Register
            </a>
        </li>
    </ul>
</div>
@endif
