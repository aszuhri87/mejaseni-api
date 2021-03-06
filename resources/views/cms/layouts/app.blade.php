<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="{{ asset('cms/assets/css/style.css')}}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/vaibhav111tandon/vov.css@latest/vov.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Hello, world!</title>
</head>

<body>
  <div class="menu-overlay pt-5 px-5 animate__animated animate__fadeInUp animate__faster">
    <h3 class="mb-4">Menu</h3>
    <div class="border-line mt-5 mb-2"></div>
    <ul class="w-100">
      <a class="my-5" href="{{ url('cart') }}">
        <li class="py-4 row-center-start"><img class="mr-2" src="{{ asset('cms/assets/img/svg/Cart3.svg') }}" alt="">Keranjang
        </li>
      </a>
      <div class="border-line w-100 my-2"></div>
      <a href="{{ url('student/dashboard') }}">
        <li class="py-4 row-center-start"><img class="mr-2" src="{{ asset('cms/assets/img/svg/dashboard.svg') }}" alt="">Dashboard</li>
      </a>
      <div class="border-line my-2"></div>
      <a href="{{ url('student/schedule') }}">
        <li class="py-4 row-center-start"><img class="mr-2" src="{{ asset('cms/assets/img/svg/schedule.svg') }}" alt="">Jadwal</li>
      </a>
      <div class="border-line my-2"></div>
      <a href="{{ url('student/invoice') }}">
        <li class="py-4 row-center-start"><img class="mr-2" src="{{ asset('cms/assets/img/svg/Wallet.svg') }}" alt="">Pembayaran
        </li>
      </a>
      <div class="border-line mb-5 mt-2"></div>
      <a href="{{ url('logout') }}">
        <li class="py-4">Keluar</li>
      </a>
    </ul>
    <div class="menu-overlay__close">
      <img src="{{ asset('cms/assets/img/svg/x.svg') }}" alt="">
    </div>
  </div>
  @include('cms.layouts.navbar')


  @yield('content')

  @stack('filter')

  @stack('banner')

  @include('cms.layouts.footer')

  @include('cms.layouts.button-navigation')



  <script src="{{ asset('cms/assets/js/script.js') }}"></script>

  <script type="text/javascript">
   $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    statusCode: {
      403: function() {
        window.location = '{{ url('admin/login') }}';
      },
      419: function() {
        window.location = '{{ url('admin/login') }}';
      }
    }
  });
</script>
<script>
    $(".see-all").click(function () {
      $(".class-owned").removeClass("fade-out-up");
      $(".class-owned").addClass("fade-in-down");
      $(".class-owned").toggle();
    });

    $("#class-owned1").click(function () {
      $(".class-owned").removeClass("fade-in-down");
      $(".class-owned").addClass("fade-out-up");
      $(".class-owned").css("display", "none");
      $("#class-name-selected").html("Basic Piano");
      $("#class-image-selected").attr("src", "././assets/img/master-lesson__banner2.jpg");
    });

  </script>
  <script>
    $("#profile-mobile").click(function () {
      $(".menu-overlay").css("display", "block");
    });
    $(".menu-overlay__close").click(function () {
      $(".menu-overlay").css("display", "none");
    });
  </script>

  <script type="text/javascript">
    window.searchDelay = function(callback, ms) {
        var timer = 0;
        return function() {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function() {
                callback.apply(context, args);
            }, ms || 0);
        };
    }
  </script>
@stack('script')

</body>

</html>
