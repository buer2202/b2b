<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title')</title>
    @include('home.layouts.links')
    @yield('css')
</head>
<body>
    <div class="layui-layout" style="background-color: #393D49;">
        <div class="layui-header" style="width: 1140px; margin: 0 auto;">
            <div style="width: 200px;height: 100%;line-height: 60px;color: #009688;font-size: 16px;">{{ config('app.name') }}</div>
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item"><a class="layui-icon" href="/" style="font-size: 30px; color: #1E9FFF;">&#xe68e;</a></li>
                @if (Route::has('login'))
                    @auth
                        <li class="layui-nav-item"><a href="{{ route('home.user.index') }}">用户中心</a></li>
                        <li class="layui-nav-item">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">注销</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                        </li>
                    @else
                        <li class="layui-nav-item"><a href="{{ route('login') }}">登录</a></li>
                        <li class="layui-nav-item"><a href="{{ route('register') }}">注册</a></li>
                    @endauth
                @endif
            </ul>
        </div>
    </div>
    <div class="layui-main">
        <!-- 内容主体区域 -->
        <div class="content">@yield('content')</div>
    </div>

    @include('home.layouts.script')
    @yield('js')
</body>
</html>
