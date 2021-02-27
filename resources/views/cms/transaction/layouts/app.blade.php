<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <base href="{{ asset('cms/assets') }}">
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? 'Mejaseni - '.$title : 'Mejaseni'}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Hello, world!</title>
</head>

<body>
    <div class="cart-container">
        <div class="row">
            <div class="col-12">
                <div class="cart__wrapper mt-3 mb-5 my-md-5 pt-4 pb-0 pb-md-3 px-3 px-md-5">
                    <div class="row-center-spacebetween">
                        <a class="navbar-brand row-center" href="#">
                            <img class="logo mr-1" src="assets/img/logo.png" alt="">
                            mejaseni
                        </a>
                        <a href="javascript:void(0);">
                            <div class="circle-wrap">
                                <div class="circle">
                                    <div class="mask full">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <div class="inside-circle">
                                        <img class="rounded-circle profile-img" id="profile-img" src="assets/img/coach.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="profile-dismiss__overlay"></div>
                        <div class="profile__wrapper animate__animated animate__fadeIn animate__faster">
                            <ul>
                                <a href="{{url('cart')}}">
                                    <li>
                                        <img class="mr-2" src="assets/img/svg/Cart3.svg" alt="">
                                        Keranjang
                                        <div class="nav-badge">4 </div>
                                    </li>
                                </a>
                                <div class="border-line"></div>
                                <a href="#">
                                    <li><img class="mr-2" src="assets/img/svg/dashboard.svg" alt="">Dashboard</li>
                                </a>
                                <a href="#">
                                    <li><img class="mr-2" src="assets/img/svg/schedule.svg" alt="">Jadwal</li>
                                </a>
                                <a href="#">
                                    <li><img class="mr-2" src="assets/img/svg/Wallet.svg" alt="">Pembayaran<div
                                            class="nav-badge">1
                                        </div>
                                    </li>
                                </a>
                                <div class="border-line"></div>
                                <a href="#">
                                    <li>Keluar</li>
                                </a>
                            </ul>
                        </div>
                    </div>
                    <div class="border-line mt-4 mb-5"></div>

                    @include('cms.transaction.layouts.stepper')

                    @yield('content')

                </div>
            </div>
        </div>
    </div>

    @stack('additional')

    @include('cms.transaction.layouts.script')

    @stack('script')
</body>

</html>
