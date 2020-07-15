@extends('home.layouts.base')

@section('content')
<form class="layui-form" id="search-form">
    <div class="layui-form-item">
        <div class="layui-input-inline" style="width: 100px;">
            <input type="text" class="layui-input" id="date-start" name="date_start" value="{{ $dateStart }}" placeholder="开始时间">
        </div>
        <div class="layui-form-mid">-</div>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="text" class="layui-input" id="date-end" name="date_end" value="{{ $dateEnd }}" placeholder="结束时间">
        </div>
        <div class="layui-input-inline" style="width: 200px;">
            <button class="layui-btn" type="submit">查询</button>
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
            <th>日期</th>
            <th>余额</th>
            <th>冻结</th>
            <th>当日加款</th>
            <th>累计加款</th>
            <th>当日提现</th>
            <th>累计提现</th>
            <th>当日消费</th>
            <th>累计消费</th>
            <th>当日退款</th>
            <th>累计退款</th>
            <th>当日支出</th>
            <th>累计支出</th>
            <th>当日收入</th>
            <th>累计收入</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dataList as $data)
            <tr>
                <td>{{ $data->date }}</td>
                <td>{{ $data->balance + 0 }}</td>
                <td>{{ $data->frozen + 0 }}</td>
                <td>{{ $data->recharge + 0 }}</td>
                <td>{{ $data->total_recharge + 0 }}</td>
                <td>{{ $data->withdraw + 0 }}</td>
                <td>{{ $data->total_withdraw + 0 }}</td>
                <td>{{ $data->consume + 0 }}</td>
                <td>{{ $data->total_consume + 0 }}</td>
                <td>{{ $data->refund + 0 }}</td>
                <td>{{ $data->total_refund + 0 }}</td>
                <td>{{ $data->expend + 0 }}</td>
                <td>{{ $data->total_expend + 0 }}</td>
                <td>{{ $data->income + 0 }}</td>
                <td>{{ $data->total_income + 0 }}</td>
            </tr>
        @empty
            <tr><td colspan="99">暂无记录</td></tr>
        @endforelse
    </tbody>
</table>

{{ $dataList->appends(['date_start' => $dateStart, 'date_end' => $dateEnd])->links() }}
@endsection

@section('js')
<script>
layui.use(['laydate', 'form'], function () {
    var laydate = layui.laydate;

    laydate.render({elem: '#date-start'});
    laydate.render({elem: '#date-end'});
});
</script>
@endsection
