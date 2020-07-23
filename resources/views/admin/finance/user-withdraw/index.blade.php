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
            @foreach ($config['withdraw']['status'] as $key => $value)
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
        <th>用户ID</th>
        <th>托管单位</th>
        <th>收款账号</th>
        <th>收款账号类型</th>
        <th>对公/对私</th>
        <th>收款人姓名</th>
        <th>申请金额</th>
        <th>状态</th>
        <th>备注</th>
        <th>支付类型</th>
        <th>外部单号</th>
        <th>实提金额</th>
        <th>创建时间</th>
        <th>最后更新</th>
        <th style="width: 150px">操作</th>
    </tr>
    @foreach ($dataList as $data)
    <tr>
        <td>{{ $data->no }}</td>
        <td>{{ $data->user_id }}</td>
        <td>{{ $data->trustee }}</td>
        <td>{{ $data->receive_account }}</td>
        <td>{{ $config['settlement_account']['type'][$data->receive_account_type] }}</td>
        <td>{{ $config['settlement_account']['acc_type'][$data->acc_type] }}</td>
        <td>{{ $data->name }}</td>
        <td>{{ (float)$data->fee }}</td>
        <td>{{ $config['withdraw']['status'][$data->status] }}</td>
        <td>{{ $data->remark }}</td>
        <td>{{ $config['withdraw']['pay_type'][$data->pay_type] }}</td>
        <td>{{ $data->external_order_id ?: '--' }}</td>
        <td>{{ $data->real_fee ? (float)$data->real_fee : '--' }}</td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>
        <td>
            @switch ($data->status)
            @case (1)
            <button class="btn btn-success btn-xs audit"
                data-url="{{ route('admin.finance.user-withdraw.department', $data->id) }}">部门审核</button>
            @break
            @case (2)
            <button class="btn btn-warning btn-xs audit"
                data-url="{{ route('admin.finance.user-withdraw.finance', $data->id) }}">财务审核</button>
            <button class="btn btn-danger btn-xs audit"
                data-url="{{ route('admin.finance.user-withdraw.refuse', $data->id) }}">拒绝</button>
            @break
            @case (3)
            <button class="btn btn-warning btn-xs offline-withdraw"
                data-url="{{ route('admin.finance.user-withdraw.offline-pay', $data->id) }}">线下提现</button>
            <button class="btn btn-danger btn-xs audit"
                data-url="{{ route('admin.finance.user-withdraw.refuse', $data->id) }}">拒绝</button>
            @break
            @default
            --
            @break
            @endswitch
        </td>
    </tr>
    @endforeach
</table>

{{ $dataList->appends(Request::all())->links() }}

<div id="offline-withdraw" class="layui-form" style="padding:20px; display: none;">
    <div class="form-group">
        <label for="from_account">付款账号</label>
        <input type="text" class="form-control" id="from_account" autocomplete="off" value="">
    </div>
    <div class="form-group">
        <label for="external_order_id">付款单号</label>
        <input type="text" class="form-control" id="external_order_id" autocomplete="off">
    </div>
    <div class="form-group">
        <label for="fill-remark">备注</label>
        <input type="text" class="form-control" id="fill-remark" autocomplete="off">
    </div>
    <input type="hidden" id="offline-withdraw-url">
    <button type="button" class="btn btn-primary" id="submit-offline-withdraw">提交</button>
</div>
@endsection

@section('js')
<script>
    // 审核
    $('.audit').click(function () {
        var url = $(this).data('url');
        layer.confirm('再次确认', function (data) {
            buer_post(url);
        });
    });

    // 线下提现
    $('.offline-withdraw').click(function () {
        var url = $(this).data('url');
        $('#offline-withdraw-url').val(url);

        layer.open({
            type: 1,
            shade: false,
            title: '填写提现信息',
            area: ['420px', 'auto'],
            content: $('#offline-withdraw')
        });
    });

    $('#submit-offline-withdraw').click(function () {
        buer_post($('#offline-withdraw-url').val(), {
            from_account: $('#from_account').val(),
            external_order_id: $('#external_order_id').val(),
            remark: $('#fill-remark').val()
        });
    });
</script>
@endsection
