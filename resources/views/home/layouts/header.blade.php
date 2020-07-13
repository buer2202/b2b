<!-- 主菜单 -->
<div class="layui-header">
    <div class="layui-logo">{{ config('app.name') }}</div>
    <ul class="layui-nav layui-layout-left">
        @foreach ($menus as $group => $menu)
            @php try { $menuUrl = route($menu['items'][0]['name']); } catch (\Exception $e) { continue; } @endphp
            <li class="layui-nav-item {{ $route[1] == $group ? 'layui-this' : '' }}"><a href="{{ $menuUrl }}">{{ $menu['group_name'] }}</a></li>
        @endforeach
    </ul>
    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item">
            <a href="javascript:;"><img src="http://t.cn/RCzsdCq" class="layui-nav-img">{{ auth()->user()->real_name }}{{ session('user_child') ? '(' . session('user_child')->name . ')' : '' }}</a>
            <dl class="layui-nav-child">
                <dd>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">注销</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                </dd>
            </dl>
        </li>
    </ul>
</div>

<!-- 子菜单 -->
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <ul class="layui-nav layui-nav-tree">
            @if (isset($menus[$route[1]]))
                @foreach ($menus[$route[1]]['items'] as $sideNav)
                    @if ($sideNav['menu_click'] == 1)
                        @php try { $sideNavUrl = route($sideNav['name']); } catch (\Exception $e) { mylog('menu-error', $e->getMessage()); continue; } @endphp
                        <li class="layui-nav-item {{ $currentRouteName == $sideNav['name'] ? 'layui-this' : '' }}"><a href="{{ $sideNavUrl }}">{{ $sideNav['title'] }}</a></li>
                    @else
                        <li class="side-nav-readonly {{ $currentRouteName == $sideNav['name'] ? 'layui-this' : '' }}">└ {{ $sideNav['title'] }}</li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</div>
