@php
    if(Auth::guard('admin')->check()){
        $name =  Auth::guard('admin')->user()->name;
        $email = Auth::guard('admin')->user()->email;
        $expertise = 'Admin';
    }elseif(Auth::guard('coach')->check()){
        $name = Auth::guard('coach')->user()->name;
        $email = Auth::guard('coach')->user()->email;
        $expertise = Auth::guard('coach')->user()->expertise;
    }elseif(Auth::guard('student')->check()){
        $name = Auth::guard('student')->user()->name;
        $email = Auth::guard('student')->user()->email;
        $expertise = Auth::guard('student')->user()->expertise;
    }
@endphp
<!-- begin::User Panel-->
<div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
    <!--begin::Header-->
    <div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
        <h3 class="font-weight-bold m-0">
            User Profile
        </h3>
        <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
            <i class="ki ki-close icon-xs text-muted"></i>
        </a>
    </div>
    <!--end::Header-->

    <!--begin::Content-->
    <div class="offcanvas-content pr-5 mr-n5">
        <!--begin::Header-->
        <div class="d-flex align-items-center mt-5">
            <div class="symbol symbol-100 mr-5">
                <div class="symbol-label" style="background-image:url('assets/media/users/300_21.jpg')"></div>
                <i class="symbol-badge bg-success"></i>
            </div>
            <div class="d-flex flex-column">
                <a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
                    {{$name}}
                </a>
                <div class="text-muted mt-1">
                    {{$expertise}}
                </div>
                <div class="navi mt-2">
                    <a href="#" class="navi-item">
                        <span class="navi-link p-0 pb-2">
                            <span class="navi-icon mr-1">
                                <span class="svg-icon svg-icon-lg svg-icon-primary">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-notification.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z"
                                                fill="#000000" />
                                            <circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon--></span> </span>
                            <span class="navi-text text-muted text-hover-primary">{{$email}}</span>
                        </span>
                    </a>

                    <a href="{{url('logout')}}" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">Sign Out</a>
                </div>
            </div>
        </div>
        <!--end::Header-->
        @if (Auth::guard('student')->check())
        <div class="separator separator-dashed mt-8 mb-5"></div>
        <div class="row">
            <div class="col">
                <div class="d-flex align-items-center mt-5">
                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"/>
                            <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                            <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                        </g>
                    </svg><!--end::Svg Icon--></span>
                    <div class="d-flex flex-column ml-5">
                        <a href="{{ url('student/profile') }}" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">Profil Saya</a>
                        <span class="text-muted">Kelola Profil Saya</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-dashed mt-8 mb-5"></div>
        <div class="row">
            <div class="col">
                <div class="d-flex align-items-center mt-5">
                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                            <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                            <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                        </g>
                    </svg><!--end::Svg Icon--></span>
                    <div class="d-flex flex-column ml-5">
                        <a href="{{ url('student/rekening') }}" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">Rekening</a>
                        <span class="text-muted">Kelola Rekening Saya</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <!--end::Content-->
</div>
<!-- end::User Panel-->

<div id="kt_quick_cart" class="offcanvas offcanvas-right-chart p-10" style="width: 500px !important">
    <!--begin::Header-->
    <div class="offcanvas-header d-flex align-items-center justify-content-between pb-7" kt-hidden-height="47" style="">
        <h4 class="font-weight-bold m-0">Cart</h4>
        <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_cart_close">
            <i class="ki ki-close icon-xs text-muted"></i>
        </a>
    </div>
    <!--end::Header-->
    <!--begin::Content-->
    <div class="offcanvas-content">
        <!--begin::Wrapper-->
        <form id="form-pay">
        <div class="offcanvas-wrapper mb-5 scroll-pull scroll ps ps--active-y" style="height: 123px; overflow: hidden;">
            <div class="cart">

            </div>
        <div class="ps__rail-x" style="left: 0px; bottom: -50px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 50px; height: 123px; right: -2px;"><div class="ps__thumb-y" tabindex="0" style="top: 8px; height: 40px;"></div></div></div>
        <!--end::Wrapper-->
        <!--begin::Purchase-->
        <div class="offcanvas-footer" kt-hidden-height="112" style="">
            <div class="d-flex align-items-center justify-content-between mb-7">
                <span class="font-weight-bold text-muted font-size-sm mr-2">Total Bayar</span>
                <span class="font-weight-bolder text-primary text-right total-price"></span>
            </div>
            <div>
                <button type="submit" style="width: 100%" class="btn btn-primary text-weight-bold">Bayar Sekarang</button>
            </div>
        </div>
        </form>
        <!--end::Purchase-->
    </div>
    <!--end::Content-->
</div>
