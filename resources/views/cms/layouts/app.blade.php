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
  @include('cms.layouts.navbar')


@yield('content')

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
@stack('script')

</body>

</html>
