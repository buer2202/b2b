@extends('admin.layouts.base')

@section('content')
<form class="form-inline">
    <div class="form-group">
        <label for="no">提现单号：</label>
        <input type="text" class="form-control" id="no" name="no" value="{{ Request::input('no') }}">
    </div>
    &nbsp;
    <div class="form-group">
        <label for="user-id">用户ID：</label>
        <input type="text" class="form-control" id="user-id" name="user_id" value="{{ Request::input('user_id') }}">
    </div>
    &nbsp;
    <div class="form-group">
        <label for="status">状态：</label>
        <select class="form-control" name="status">
            <option value="">全部</option>
            @foreach ($status['status'] as $key => $value)
                <option value="{{ $key }}" {{ $key == Request::input('status') ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
    &nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
</form>

<table class="table table-striped table-condensed m-t">
    <tr>
        <th>单号</th>
        <th>外部订单号</th>
        <th>用户ID</th>
        <th>支付类型</th>
        <th>托管单位</th>
        <th>收款账号</th>
        <th>收款账号类型</th>
        <th>对公/对私</th>
        <th>收款人姓名</th>
        <th>申请金额</th>
        <th>实提金额</th>
        <th>状态</th>
        <th>备注</th>
        <th>创建时间</th>
        <th>最后更新</th>
        <th style="width: 150px">操作</th>
    </tr>
    @foreach ($dataList as $data)
        <tr>
            <td>{{ $data->no }}</td>
            <td>{{ $data->external_order_id }}</td>
            <td>{{ $data->user_id }}</td>
            <td>{{ $status['pay_type'][$data->pay_type] }}</td>
            <td>{{ $data->trustee }}</td>
            <td class="receive_account">{{ $data->receive_account }}</td>
            <td>{{ $userTradingAccount['type'][$data->receive_account_type] }}</td>
            <td>{{ $userTradingAccount['acc_type'][$data->acc_type] }}</td>
            <td>{{ $data->name }}</td>
            <td class="fee">{{ $data->fee + 0 }}</td>
            <td>{{ $data->real_fee + 0 }}</td>
            <td>{{ $status['status'][$data->status] }}</td>
            <td>{{ $data->remark }}</td>
            <td>{{ $data->created_at }}</td>
            <td>{{ $data->updated_at }}</td>
            <td>
                @switch ($data->status)
                    @case (1)
                        <button class="btn btn-success btn-xs audit" data-url="{{ route('admin.finance.user-withdraw.department', $data->id) }}">部门审核</button>
                        @break
                    @case (2)
                        <button class="btn btn-warning btn-xs audit" data-url="{{ route('admin.finance.user-withdraw.finance', $data->id) }}">财务审核</button>
                        <button class="btn btn-danger btn-xs audit" data-url="{{ route('admin.finance.user-withdraw.refuse', $data->id) }}">财务拒绝</button>
                        @break

                    @case (3)
                        @if (config('app.platform_id') == 1)
                            <button class="btn btn-info btn-xs fillback" data-url="{{ route('admin.finance.user-withdraw.order-id-backfill', $data->id) }}">补单</button>
                            <button class="btn btn-warning btn-xs auto-transfer" data-url="{{ route('admin.finance.user-withdraw.auto-transfer', $data->id) }}">自动</button>
                        @else
                            <button class="btn btn-warning btn-xs fulu-auto" data-url="{{ route('admin.finance.user-withdraw.fulu', $data->id) }}">一起游提现</button>
                        @endif
                        <button class="btn btn-danger btn-xs audit" data-url="{{ route('admin.finance.user-withdraw.refuse', $data->id) }}">驳回</button>
                        @break
                    @case (7)
                    @case (8)
                        <button class="btn btn-info btn-xs fulu-info" data-url="{{ route('admin.finance.user-withdraw.fulu-info', $data->id) }}">办款信息</button>
                        @break
                @endswitch
            </td>
        </tr>
    @endforeach
</table>

{{ $dataList->appends(Request::all())->links() }}

<div id="fillback-window" class="layui-form" style="padding:20px; display: none;">
    <div class="form-group">
        <label for="from_account">出钱账号</label>
        <input type="text" class="form-control" id="from_account" autocomplete="off" value="{{ $defaultFromAccount }}">
    </div>
    <div class="form-group">
        <label for="external_order_id">打款单号</label>
        <input type="text" class="form-control" id="external_order_id" autocomplete="off">
    </div>
    <div class="form-group">
        <label for="amount">打款金额</label>
        <input type="text" class="form-control" id="amount" autocomplete="off">
    </div>
    <div class="form-group">
        <label for="fill-remark">备注</label>
        <input type="text" class="form-control" id="fill-remark" autocomplete="off" placeholder="可以留空">
    </div>
    <input type="hidden" id="fillback-url">
    <button type="button" class="btn btn-primary" id="submit-fillback">提交</button>
</div>

<div id="auto-window" class="layui-form" style="padding:20px; display: none;">
    <div class="form-group">
        <label for="from_account">从账号</label>
        <input type="text" class="form-control" id="from_account" autocomplete="off" value="{{ $defaultFromAccount }}">
        <label id="auto-info"></label>
    </div>
    <div class="form-group">
        <label for="auto-remark">备注</label>
        <input type="text" class="form-control" id="auto-remark" autocomplete="off" placeholder="可以留空">
    </div>
    <input type="hidden" id="auto-url">
    <button type="button" class="btn btn-primary" id="submit-auto">提交</button>
</div>
@endsection

@section('js')
<script>
// 审核
$('.audit').click(function () {
    var url = $(this).data('url');

    layer.confirm('再次确认', function (data) {
        var loading = layer.load(0, {shade: 0.3});

        $.post(url, function (data) {
            layer.close(loading);

            if (data.status == 1) {
                layer.alert('操作成功', function () {
                    window.location.reload();
                });
            } else {
                layer.alert(data.message, {icon: 5});
            }
        }, 'json');
    });
});

// 回填单号
$('.fillback').click(function () {
    var url = $(this).data('url');
    $('#fillback-url').val(url);

    layer.open({
        type: 1,
        shade: false,
        title: '填写提现补单信息',
        area: ['420px', 'auto'],
        content: $('#fillback-window')
    });
});

$('#submit-fillback').click(function () {
    var loading = layer.load(0, {shade: 0.3});

    $.ajax({
        url: $('#fillback-url').val(),
        type: "POST",
        dataType: "json",
        data: {
            external_order_id: $('#external_order_id').val(),
            from_account: $('#from_account').val(),
            amount: $('#amount').val(),
            remark: $('#fill-remark').val()
        },
        error: function (data) {
            layer.close(loading);
            var responseJSON = data.responseJSON.errors;
            for (var key in responseJSON) {
                layer.msg(responseJSON[key][0]);
                break;
            }
        },
        success: function (data) {
            layer.close(loading);
            if (data.status == 1) {
                layer.alert('操作成功', {icon:6}, function () {
                    parent.location.reload();
                });
            } else {
                layer.alert(data.message, {icon: 5});
            }
        }
    });
});

// 自动转账
$('.auto-transfer').click(function () {
    var url = $(this).data('url');
    $('#auto-url').val(url);

    var receiveAccount = $(this).parent().siblings('.receive_account').text();
    var fee = $(this).parent().siblings('.fee').text();
    $('#auto-info').text('转账 ' + fee + ' 元到 ' + receiveAccount);

    layer.open({
        type: 1,
        shade: false,
        title: '填写自动提现信息',
        area: ['420px', 'auto'],
        content: $('#auto-window')
    });
});

$('#submit-auto').click(function () {
    layer.confirm('【自动转账】，将会自动向目标账户转账相应金额，确定吗？', function () {
        var loading = layer.load(0, {shade: 0.2});

        $.post($('#auto-url').val(), {
            from_account: $('#from_account').val(),
            remark: $('#auto-remark').val()
        }, function (data) {
            layer.close(loading);
            if (data.status == 1) {
                layer.alert('转账成功', {icon: 6}, function () {
                    parent.location.reload();
                });
            } else {
                layer.alert(data.message, {icon: 5});
            }
        }, 'json');
    });

});

$('.fulu-auto').click(function () {
    var url = $(this).data('url');

    layer.confirm('【一起游提现】，将会自动向福禄财务接口发起自动提现请求，确定吗？', function () {
        var loading = layer.load(0, {shade: 0.2});

        $.post(url, function (data) {
            layer.close(loading);
            if (data.status == 1) {
                layer.alert('操作成功', {icon: 6}, function () {
                    parent.location.reload();
                });
            } else {
                layer.alert(data.message, {icon: 5});
            }
        }, 'json');
    });
});

$('.fulu-info').click(function () {
    var loading = layer.load(0, {shade: 0.2});

    $.get($(this).data('url'), function (data) {
        layer.close(loading);
        if (data.status == 1) {
            var html = '<p>办款单号: ' + data.content.bill_id + '</p>'
                     + '<p>办款时间: ' + data.content.bill_date + '</p>'
                     + '<p>办款人: ' + data.content.bill_user_name + '</p>'
                     + '<p>付款账户: ' + data.content.pay_account + '</p>'
                     + '<p>银行全称: ' + data.content.pay_bank_full_name + '</p>'
                     + '<p>银行流水号: ' + data.content.reqnbr_id + '</p>'
                     + '<p>回传时间: ' + data.content.created_at + '</p>'
                     + '<p>描述: ' + data.content.result + '</p>';

            layer.alert(html);
        } else {
            layer.alert(data.message, {icon: 5});
        }
    }, 'json');
});
</script>
@endsection
