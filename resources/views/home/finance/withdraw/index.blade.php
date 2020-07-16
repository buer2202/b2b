@extends('home.layouts.base')

@section('content')
<form class="layui-form" id="search-form">
    <div class="layui-form-item">
        <div class="layui-input-inline" style="width: 100px;">
            <input type="text" class="layui-input" id="time-start" name="time_start" value="{{ Request::input('time_start') }}" placeholder="开始时间">
        </div>
        <div class="layui-form-mid">-</div>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="text" class="layui-input" id="time-end" name="time_end" value="{{ Request::input('time_end') }}" placeholder="结束时间">
        </div>
        <div class="layui-input-inline" style="width: 100px;">
            <select name="status">
                <option value="">全部状态</option>
                @foreach ($assetWithdrawStatus as $key => $value)
                    <option value="{{ $key }}" {{ $key == Request::input('status') ? 'selected' : '' }}>{{ $key }}. {{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="layui-input-inline" style="width: 400px;">
            <button class="layui-btn" type="submit">查询</button>
            <button class="layui-btn layui-btn-normal" type="button" id="add">立即提现</button>
        </div>
    </div>
</form>

<table class="layui-table" lay-size="sm">
    <colgroup>
        <col width="150">
        <col>
    </colgroup>
    <thead>
        <tr>
            <th>提现单号</th>
            <th>托管单位</th>
            <th>收款账号</th>
            <th>收款人姓名</th>
            <th>收款金额</th>
            <th>状态</th>
            <th>备注</th>
            <th>创建时间</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dataList as $data)
            <tr>
                <td>{{ $data->no }}</td>
                <td>{{ $data->trustee }}</td>
                <td>{{ $data->receive_account }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->fee }}</td>
                <td>{{ $assetWithdrawStatus[$data->status] }}</td>
                <td>{{ $data->remark }}</td>
                <td>{{ $data->created_at }}</td>
            </tr>
        @empty
            <tr><td colspan="99">暂无记录</td></tr>
        @endforelse
    </tbody>
</table>

{{ $dataList->appends(Request::all())->links() }}

<div id="add-window" class="layui-form" style="padding:20px 50px 0 0; display: none;">
    @if ($settlementAccount->isNotEmpty())
        <div class="layui-form-item">
            <label class="layui-form-label">收款账号</label>
            <div class="layui-input-block">
                <select id="account-id">
                    @foreach ($settlementAccount as $account)
                        <option value="{{ $account->id }}">{{ $account->account }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">提现金额</label>
            <div class="layui-input-block">
                <input type="text" id="fee" class="layui-input" autocomplete="off" />
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" id="add-submit">提交</button>
            </div>
        </div>
    @else
        <div style="margin-left: 20px;">
            您尚未设置结算账号，请设置后再提现。
            <a type="button" class="layui-btn layui-btn-normal layui-btn-xs" href="{{ route('home.finance.settlement-account.index') }}">去设置</a>
        </div>
    @endif
</div>
@endsection

@section('js')
<script>
layui.use(['laydate', 'form'], function () {
    var laydate = layui.laydate;

    laydate.render({elem: '#time-start'});
    laydate.render({elem: '#time-end'});

    $('#add').click(function () {
        layer.open({
            type: 1,
            shade: false,
            title: '填写提现信息',
            area: ['400px', '250px'],
            content: $('#add-window')
        });
    });

    $('#add-submit').click(function () {
        var load = layer.load({shade: 0.2});

        $.ajax({
            url: "{{ route('home.finance.withdraw.store') }}",
            type: 'post',
            dataType: 'json',
            data: {
                account_id: $('#account-id').val(),
                fee: $('#fee').val()
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
                    layer.alert('提现申请成功，工作人员将在T+1个工作日内审核', {icon: 6}, function () {
                        parent.location.reload();
                    });
                } else {
                    layer.alert(data.message, {icon: 5});
                }
            }
        });
    });

});
</script>
@endsection
