@extends('layouts.app')

@section('title', '重设密码')

@section('content')
<blockquote class="layui-elem-quote layui-quote-nm">
    <div class="layui-tab layui-tab-brief" lay-filter="user">
        <ul class="layui-tab-title">
            <li class="layui-this">重设密码</li>
        </ul>
        <div class="layui-form layui-tab-content" style="padding: 20px 0;">
            <div class="layui-tab-item layui-show">
                <div class="layui-form layui-form-pane">
                    <form method="post" action="{{ route('password.request') }}">
                        <div class="layui-form-item">
                            <label for="email" class="layui-form-label">注册邮箱</label>
                            <div class="layui-input-inline">
                                <input type="text" id="email" name="email" lay-verify="email" autocomplete="off" class="layui-input" value="{{ $email or old('email') }}" />
                            </div>
                            @if ($errors->has('email'))
                                <div class="layui-form-mid red">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="layui-form-item">
                            <label for="password" class="layui-form-label">新密码</label>
                            <div class="layui-input-inline">
                                <input type="password" id="password" name="password" lay-verify="required" autocomplete="off" class="layui-input" />
                            </div>
                            @if ($errors->has('password'))
                                <div class="layui-form-mid red">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="layui-form-item">
                            <label for="password-confirm" class="layui-form-label">确认密码</label>
                            <div class="layui-input-inline">
                                <input type="password" id="password-confirm" name="password_confirmation" lay-verify="required" autocomplete="off" class="layui-input" />
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <div class="layui-form-mid red">{{ $errors->first('password_confirmation') }}</div>
                            @endif
                        </div>
                        <div class="layui-form-item">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <button class="layui-btn" lay-filter="*" lay-submit="">重设密码</button>
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
layui.use('form', function () {
    var form = layui.form;
});
</script>
@endsection
