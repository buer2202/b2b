@extends('layouts.app')

@section('title', '忘记密码')

@section('content')
<blockquote class="layui-elem-quote layui-quote-nm">
    <div class="layui-tab layui-tab-brief" lay-filter="user">
        <ul class="layui-tab-title">
            <li class="layui-this">忘记密码</li>
        </ul>
        <div class="layui-form layui-tab-content" style="padding: 20px 0;">
            <div class="layui-tab-item layui-show">
                <div class="layui-form layui-form-pane">
                    <form method="post" action="{{ route('password.email') }}">
                        <div class="layui-form-item">
                            <label for="email" class="layui-form-label">注册邮箱</label>
                            <div class="layui-input-inline">
                                <input type="text" id="email" name="email" lay-verify="email" autocomplete="off" class="layui-input" value="{{ old('email') }}" />
                            </div>
                            @if ($errors->has('email'))
                                <div class="layui-form-mid red">{{ $errors->first('email') }}</div>
                            @endif
                            @if ($message)
                                <div class="layui-form-mid green">{{ $message }}</div>
                            @endif
                        </div>
                        <div class="layui-form-item">
                            {{ csrf_field() }}
                            <button class="layui-btn" lay-filter="send-email" lay-submit="">发送重置密码链接</button>
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

    form.on('submit(send-email)', function (data) {
        layer.load(0, {shade: 0.3})
    });
});
</script>
@endsection
