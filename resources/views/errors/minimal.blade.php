<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NRS - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{asset('storage/favicon/favicon.ico')}}">
    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<main class="container mt-5 pt-5 d-flex flex-column justify-content-center align-items-center">
    <div>
        <div class="offset-4 col-4 mb-3">
            <img class="img-fluid" src="{{asset('storage/logos/logo.png')}}" alt="NRS Logo">
        </div>
        <div class="text-center text-lowercase">
            <h2 class="serif">
                @yield('code') - @yield('message')
            </h2>
        </div>
    </div>
</main>
</body>
</html>
