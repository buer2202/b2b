@extends('layouts.app')

@section('title', '登陆')

@section('content')
<blockquote class="layui-elem-quote layui-quote-nm">
    <div class="layui-tab layui-tab-brief" lay-filter="user">
        <ul class="layui-tab-title">
            <li class="layui-this">登录</li>
            <li><a href="{{ route('register') }}">注册</a></li>
        </ul>
        <div class="layui-form layui-tab-content" style="padding: 20px 0;">
            <div class="layui-tab-item layui-show">
                <div class="layui-form layui-form-pane">
                    <form method="post" action="{{ route('login') }}">
                        <div class="layui-form-item">
                            <label for="email" class="layui-form-label">账号</label>
                            <div class="layui-input-inline">
                                <input type="text" id="email" name="email" lay-verify="email" autocomplete="off" class="layui-input" value="{{ old('email') }}" />
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="password" class="layui-form-label">密码</label>
                            <div class="layui-input-inline">
                                <input type="password" id="password" name="password" required="" lay-verify="required" autocomplete="off" class="layui-input" />
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-inline">
                                <input type="checkbox" name="remember" title="记住我" lay-skin="primary" {{ old('remember') ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            {{ csrf_field() }}
                            <button class="layui-btn" lay-filter="*" lay-submit="">立即登录</button>
                            <span style="padding-left:20px;">
                                <a href="{{ route('password.request') }}">忘记密码？</a>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</blockquote>
@endsection

@section('js')
<script type="text/javascript">
layui.use(['layer', 'form'], function () {
    var layer = layui.layer;
    var form = layui.form;

    @if ($errors->any())
        layer.msg("{{ $errors->getBag('default')->first() }}", {icon: 5, anim:6});
    @endif

});
</script>
@endsection
