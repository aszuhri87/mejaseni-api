<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <base href="{{asset('/')}}">
    <meta charset="utf-8" />
    <title>Mejaseni | Sign In</title>
    <meta name="description" content="Mejaseni Singin Page" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->


    <!--begin::Page Custom Styles(used by this page)-->
    <link href="assets/css/pages/login/login-4.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->

    <!--begin::Layout Themes(used by all pages)-->

    <link href="assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css" />
    <!--end::Layout Themes-->

    <link rel="shortcut icon" href="assets/images/favicon.png" />

</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">

    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Content-->
            <div class="login-container order-2 order-lg-1 d-flex flex-center flex-row-fluid px-7 pt-lg-0 pb-lg-0 pt-4 pb-6 bg-white">
                <!--begin::Wrapper-->
                <div class="login-content d-flex flex-column pt-lg-0 pt-12">

                    <!--begin::Signin-->
                    <div class="login-form">
                        <!--begin::Form-->
                        <form class="form" id="kt_login_signin_form" method="POST"  autocomplete="off">
                            @csrf
                            <div class="pb-5 pb-lg-10">
                                <h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Selamat,<br>reset password telah berhasil</h3>
                                <p class="text-muted">Silakan cek email anda.</p>
                            </div>

                            <!--begin::Action-->
                            <div class="pb-lg-0 pb-5">
                                <a href="{{url('login')}}" id="kt_login_singin_form_submit_button" class="btn btn-outline-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Login</a>
                            </div>
                            <!--end::Action-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Signin-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--begin::Content-->

            <!--begin::Aside-->
            <div class="login-aside order-1 order-lg-2 bgi-no-repeat bgi-position-x-right">
                <div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" style="background-image: url(assets/images/login-visual.svg);">
                    <a href="{{ url('/') }}" class="login-logo ml-5">
                        <img src="assets/images/logo-name.png" class="max-h-70px mt-10 ml-5" alt="" />
                    </a>
                </div>
            </div>
            <!--end::Aside-->
        </div>
        <!--end::Login-->
    </div>
    <!--end::Main-->

    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };

    </script>
    <!--end::Global Config-->

    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <!--end::Global Theme Bundle-->

    <!--begin::Page Scripts(used by this page)-->
    <script src="assets/js/pages/custom/login/login-4.js"></script>
    <!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>
