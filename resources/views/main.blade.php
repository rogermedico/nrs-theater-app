<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>
    <meta name="description" content="{{__('NRS Theater Reservation APP')}}">
    <meta name="keywords" content="NRS theater reservation">
    <meta name="author" content="Roger Medico">
    <link rel="icon" type="image/png" href="{{asset('storage/favicon/favicon.ico')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<header>
{{--    @include('navbar')--}}
</header>
<main class="container">
{{--    @yield('main-content')--}}
    <p>asdf</p>
</main>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>
