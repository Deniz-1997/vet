<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FAVICON -->
    <link rel="icon" href="{{ url('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ url('favicon2x.ico') }}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Corvet Notify') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/vuetify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
<!--[if lt IE 10]>
<p class="browsehappy">Вы используете <strong>УСТАРЕВШИЙ Internet Explorer</strong> браузер. Пожалуйста, <a
        href="http://browsehappy.com/">обновите ваш Браузер</a> чтобы увидеть больше возможностей на сайте!</p>
<![endif]-->
<div id="app">
    <vue-layout></vue-layout>
</div>

<script src="{{ asset('/js/app_.js?v=').filemtime(public_path('/js/app_.js')) }}"></script>
</body>
</html>
