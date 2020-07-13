<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - {{ $breadcrumbLastname ?? '' }}</title>
    @include('home.layouts.links')
    @yield('css')

    @stack('scripts')
</head>
<body class="layui-layout-body">
    <div class="layui-layout layui-layout-admin">

        @include('home.layouts.header')

        <div class="layui-body">
            <!-- 内容主体区域 -->
            <div style="padding: 15px;">
                <span class="layui-breadcrumb">
                    <a href="{{ route('home.user.index') }}">首页</a>
                    <a><cite>{{ $breadcrumbMiddlename ?? '' }}</cite></a>
                    <a><cite>{{ $breadcrumbLastname ?? '' }}</cite></a>
                </span>

                <div class="content">@yield('content')</div>
            </div>
        </div>
    </div>

    @include('home.layouts.script')

    @yield('js')

</body>
</html>
