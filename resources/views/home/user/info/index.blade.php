@extends('home.layouts.base')

@section('content')
<blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 20px;">
    <div class="layui-row">
        <div class="layui-col-md4">
            <form class="layui-form" action="" lay-filter="user-info">
                <div class="layui-form-item">
                    <label class="layui-form-label">用户姓名：</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input layui-disabled" disabled value="{{ auth()->user()->real_name }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">用户账号：</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input layui-disabled" disabled value="{{ auth()->user()->email }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号码：</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" required  lay-verify="required|phone" placeholder="请输入手机号码" autocomplete="off" class="layui-input" value="{{ auth()->user()->phone }}">
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

    form.on('submit(user-info)', function (data) {
        $.ajax({
            url: "{{ route('home.user.info.update') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                phone: $('[name="phone"]').val(),
                qq: $('[name="qq"]').val()
            },
            error: function (data) {
                errors = data.responseJSON.errors;
                for (key in errors) {
                    layer.alert(errors[key][0], {icon: 5});
                    return false;
                }
            },
            success: function (data) {
                if (data.status === 1) {
                    layer.alert('操作成功', {icon: 6});
                } else {
                    layer.alert(data.message, {icon: 5});
                }
            }
        });

        return false;
    });
});
</script>
@endsection
