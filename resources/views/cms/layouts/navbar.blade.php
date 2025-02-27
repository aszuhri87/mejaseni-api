@php
    $segment1 = Request::segment(1);
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand row-center" href="{{ url('/') }}">
        <img class="logo mr-1" style="width: 215px;" src="{{ asset('assets/images/mejaseni-logo-black.png') }}" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav row-center container-fluid justify-content-end">
      <li class="nav-item  @if($segment1 == null ){{'active'}}@endif">
        <a class="nav-link" href="{{ url('/') }}">Home</a>
      </li>
      <li class="nav-item @if($segment1 == 'class'){{'active'}}@endif">
        <a class="nav-link" href="{{ url('class') }}">Class</a>
      </li>
      <li class="nav-item @if($segment1 == 'video-course'){{'active'}}@endif">
        <a class="nav-link" href="{{ url('video-course') }}">Tutorial Video</a>
      </li>
      <li class="nav-item @if($segment1 == 'store'){{'active'}}@endif">
        <a class="nav-link" href="{{ url('store') }}">E-Store</a>
      </li>
      <li class="nav-item @if($segment1 == 'news-event' || $segment1 == 'event-list'
      || $segment1 == 'event' || $segment1 == 'news' || $segment1 == 'news-list' ){{'active'}}@endif">
        <a class="nav-link" href="{{ url('news-event') }}">News & Event</a>
      </li>
      @if(Auth::guard('student')->check())
        <a href="#">
          <div class="circle-wrap">
            <div class="circle">

              <div class="mask full">
                <div class="progress-bar"></div>
              </div>

              <div class="mask half">
                <div class="progress-bar"></div>
              </div>

              <div class="inside-circle">
                <img class="rounded-circle profile-img" id="profile-img" src="{{ Auth::guard('student')->user()->getImageUrl() }}"
                alt="">
              </div>

            </div>
          </div>
        </a>
      @endif
    </ul>
  </div>
</nav>



<div class="profile__wrapper animate__animated animate__fadeIn animate__faster">
  <ul>
    <a href="{{ url('cart') }}">
      <li><img class="mr-2" src="{{ asset('cms/assets/img/svg/Cart3.svg') }}" alt="">Keranjang</li>
    </a>
    <div class="border-line"></div>
    <a href="{{ url('student/dashboard') }}">
      <li><img class="mr-2" src="{{ asset('cms/assets/img/svg/dashboard.svg') }}" alt="">Dashboard</li>
    </a>
    <a href="{{ url('student/schedule') }}">
      <li><img class="mr-2" src="{{ asset('cms/assets/img/svg/schedule.svg') }}" alt="">Jadwal</li>
    </a>
    <a href="{{ url('student/invoice') }}">
      <li><img class="mr-2" src="{{ asset('cms/assets/img/svg/Wallet.svg') }}" alt="">Pembayaran</li>
    </a>
    <div class="border-line"></div>
    <a href="{{ url('logout') }}">
      <li>Keluar</li>
    </a>
  </ul>
</div>

<div class="profile-dismiss__overlay"></div>
