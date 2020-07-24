@extends('home.layouts.base')

@section('content')
<blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 20px;">
    <div class="layui-row">
        <div class="layui-col-md4">
            <form class="layui-form" action="" lay-filter="user-pass">
                <div class="layui-form-item">
                    <label class="layui-form-label">原密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="origin_password" required lay-verify="pass" placeholder="请输入原始密码"
                            autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="password" required lay-verify="pass" placeholder="请输入新密码"
                            autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">确认密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="password_confirmation" required lay-verify="pass"
                            placeholder="请再次输入新密码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</blockquote>
@endsection

@section('js')
<script>
    layui.use(['layer', 'form'], function () {
        var layer = layui.layer;
        var form = layui.form;

        form.verify({
            pass: [/^[\S]{6,12}$/, '密码必须6到12位，且不能出现空格']
        });
        form.on('submit(user-pass)', function (data) {
            buer_post("{{ route('home.user.reset-password.update') }}", {
                origin_password: $('[name="origin_password"]').val(),
                password: $('[name="password"]').val(),
                password_confirmation: $('[name="password_confirmation"]').val()
            });

            return false;
        });
    });
</script>
@endsection
