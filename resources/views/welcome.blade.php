<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>
    </head>
    <body>

    <script src="{{ asset('/js/app.js') }}"></script>
    <script>
        Echo.channel('laravel_database_coach-notification')
            .listen('.coach.notification.123', e => {
                console.log(e)
            })
    </script>

    </body>
</html>
