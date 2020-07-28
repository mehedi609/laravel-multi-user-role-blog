<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')-{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="{{asset('frontend/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('frontend/css/swiper.css')}}" rel="stylesheet">
    <link href="{{asset('frontend/css/ionicons.css')}}" rel="stylesheet">

    @stack('css')

</head>
<body>
    @include('layouts.frontend.partial._header')

    @yield('content')

    @include('layouts.frontend.partial._footer')


    <script src="{{asset('frontend/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('frontend/js/tether.min.js')}}"></script>
    <script src="{{asset('frontend/js/bootstrap.js')}}"></script>

    @stack('js')
</body>
</html>
