<!doctype html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @include('home.layouts.links')
    @yield('css')
</head>
<body style="padding: 20px; min-width: 95%;">
    @yield('body')
    @include('home.layouts.script')
    @yield('js')
</body>
</html>
