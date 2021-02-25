<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="{{ asset('cms/assets/css/style.css')}}">
  <title>Hello, world!</title>
</head>

<body>
  @include('cms.layouts.navbar')


@yield('content')

@stack('banner')

@include('cms.layouts.footer')

@include('cms.layouts.button-navigation')



<script src="{{ asset('cms/assets/js/script.js') }}"></script>

@stack('script')

</body>

</html>
