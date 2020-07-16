@extends('home.layouts.base')

@section('content')
<button class="layui-btn" id="add">添加账号</button>

<table class="layui-table" lay-size="sm">
    <thead>
        <tr>
            <th>类型</th>
            <th>托管单位</th>
            <th>收/付款账号</th>
            <th>开户人姓名</th>
            <th>对公/对私</th>
            <th>创建时间</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dataList as $data)
            <tr>
                <td>{{ $config['type'][$data->type] }}</td>
                <td>{{ $data->trustee }}</td>
                <td>{{ $data->account }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $config['acc_type'][$data->acc_type] }}</td>
                <td>{{ $data->created_at }}</td>
            </tr>
        @empty
            <tr><td colspan="99">暂无数据</td></tr>
        @endforelse
    </tbody>
</table>

<div id="add-window" class="layui-form" style="padding:20px 50px 0 0; display: none;">
    <div class="layui-form-item">
        <label class="layui-form-label">账号类型</label>
        <div class="layui-input-block">
            <select id="type" lay-filter="type">
                <option value="1">支付宝</option>
                <option value="2">银行卡</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">对公/对私</label>
        <div class="layui-input-block">
            <select id="acc-type" lay-filter="type">
                <option value="1">对私</option>
                <option value="2">对公</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">托管单位</label>
        <div class="layui-input-block">
            <input type="text" id="trustee" class="layui-input" autocomplete="off" value="支付宝" />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">收/付款账号</label>
        <div class="layui-input-block">
            <input type="text" id="account" class="layui-input" autocomplete="off" />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开户人姓名</label>
        <div class="layui-input-block">
            <input type="text" id="name" class="layui-input" autocomplete="off" />
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" id="add-submit">提交</button>
            <p style="color: #FF5722;margin-top: 10px;">提交前请仔细核对，避免造成资金损失！</p>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
layui.use(['layer', 'form'], function() {
    var layer = layui.layer;
    var form  = layui.form;

    $('#add').click(function () {
        layer.open({
            type: 1,
            shade: false,
            title: '填写账号信息',
            area: ['420px', 'auto'],
            content: $('#add-window')
        });
    });

    $('#add-submit').click(function () {
        var load = layer.load({shade: 0.2});

        $.ajax({
            url: "{{ route('home.finance.settlement-account.store') }}",
            type: 'post',
            dataType: 'json',
            data: {
                type: $('#type').val(),
                trustee: $('#trustee').val(),
                account: $('#account').val(),
                name: $('#name').val(),
                acc_type: $('#acc-type').val()
            },
            error: function (data) {
                layer.close(load);
                errors = data.responseJSON.errors;
                for (key in errors) {
                    layer.alert(errors[key][0], {icon: 5});
                    return false;
                }
            },
            success: function (data) {
                layer.close(load);
                if (data.status == 1) {
                    layer.alert('操作成功', {icon: 6}, function () {
                        parent.location.reload();
                    });
                } else {
                    layer.alert(data.message, {icon: 5});
                }
            }
        });
    });

    //
    form.on('select(type)', function(data){
        if (data.value == 1) {
            $('#trustee').val('支付宝');
        } else {
            $('#trustee').val('');
        }
    });
});
</script>
@endsection
