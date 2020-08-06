<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - {{ $breadcrumbLastname ?? '' }}@yield('title')</title>
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="/bootstrap-3.3.7-dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="/css/admin.css">
    @yield('css')
</head>
<body>
@section('nav')
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ route('admin.index.index') }}">
                    {{ config('app.name') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if (auth()->guard('admin')->guest())
                        &nbsp;
                    @else
                        <!-- 特殊的组，不进入权限管理 -->
                        @if (Auth::user()->isAdministrator())
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-haspopup="true" aria-expanded="false">系统管理 <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.system.administrator.index') }}">管理员管理</a></li>
                                    <li><a href="{{ route('admin.system.role.index') }}">角色授权管理</a></li>
                                    <li><a href="{{ route('admin.system.rule.index') }}">系统权限管理</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{ route('admin.system.config.index') }}">系统配置管理</a></li>
                                </ul>
                            </li>
                        @endif

                        @foreach ($menus as $group => $menu)
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">{{ $menu['group_name'] }} <span
                                            class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    @foreach ($menu['items'] as $subMenu)
                                        {{-- 含有 separator-divider 关键字就是分割线 --}}
                                        @if (stripos($subMenu['name'], 'separator-divider'))
                                            <li role="separator" class="divider"></li>
                                        @else
                                            <li><a href="{{ route($subMenu['name']) }}">{{ $subMenu['title'] }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach

                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (auth()->guard('admin')->guest())
                        <li><a href="{{ route('admin.login') }}">Login</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ auth()->guard('admin')->user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route('admin.password.index') }}">修改密码</a></li>
                                <li>
                                    <a href="{{ route('admin.logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        注销
                                    </a>

                                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@show

@section('crumbs')
    @if (auth()->guard('admin')->check())
        <ol class="breadcrumb" style="margin-top: -20px">
            <li><a href="{{ route('admin.index.index') }}">首页</a></li>
            @switch ($route[1])
                @case ('index')
                @case ('system')
                @case ('password')
            @section('breadcrumb')
                <li>@yield('title')</li>
            @show
            @break
            @default
            @if (isset($breadcrumbMiddlename) && $breadcrumbMiddlename)
                <li>{{ $breadcrumbMiddlename }}</li>
            @endif
            @if (isset($breadcrumbLastname) && $breadcrumbLastname)
                <li>{{ $breadcrumbLastname }}</li>
            @endif
            @endswitch
        </ol>
    @endif
@show

<div class="container-fluid">@yield('content')</div>

@yield('footer')

<script src="/js/jquery-1.11.0.min.js"></script>
<script src="/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
<script src="/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="/layer/layer.js"></script>
<script src="/js/buer_post.js"></script>
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    function reload() {
        setTimeout(function () {
            location.reload();
        }, 900);
    }

    function reloadHref() {
        setTimeout(function () {
            location.href = location.href;
        }, 900);
    }

    function redirect(str) {
        setTimeout(function () {
            window.location.href = str;
        }, 900);
    }

    // 主菜单自动打开
    $(".nav li.dropdown").mouseover(function () {
        $(this).addClass("open").find("a.dropdown-toggle").attr("aria-expanded", "true");
    }).mouseout(function () {
        $(this).removeClass("open").find("a.dropdown-toggle").attr("aria-expanded", "false");
    });

    // 闪存弹框
    @if (session()->has('alert'))
    layer.alert("{{ session('alert') }}");
    @endif
</script>

@yield('js')
</body>
</html>
