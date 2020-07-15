@extends('home.layouts.base')

@section('content')
<form class="layui-form" id="search-form">
    <div class="layui-form-item">
        <div class="layui-input-inline" style="width: 100px;">
            <input type="text" class="layui-input" id="time-start" name="time_start" value="{{ $timeStart }}" autocomplete="off" placeholder="开始时间">
        </div>
        <div class="layui-form-mid">-</div>
        <div class="layui-input-inline" style="width: 100px;">
            <input type="text" class="layui-input" id="time-end" name="time_end" value="{{ $timeEnd }}" autocomplete="off" placeholder="结束时间">
        </div>
        <div class="layui-input-inline" style="width: 100px;">
            <select name="trade_type">
                <option value="">所有类型</option>
                @foreach (config('asset.type') as $key => $value)
                    <option value="{{ $key }}" {{ $key == $tradeType ? 'selected' : '' }}>{{ $key }}. {{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="layui-input-inline" style="width: 200px;">
            <input type="text" class="layui-input" name="trade_no" placeholder="相关单号">
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
            <th>流水号</th>
            <th>相关单号</th>
            <th>类型</th>
            <th>变动金额</th>
            <th>说明</th>
            <th>余额</th>
            <th>冻结</th>
            <th>时间</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dataList as $data)
            <tr>
                <td>{{ $data->id }}</td>
                <td>{{ $data->trade_no }}</td>
                <td>{{ $assetTradeTypeUser[$data->trade_type] }}</td>
                <td>{{ $data->fee + 0 }}</td>
                <td>{{ $data->remark }}</td>
                <td>{{ $data->balance + 0 }}</td>
                <td>{{ $data->frozen + 0 }}</td>
                <td>{{ $data->created_at }}</td>
            </tr>
        @empty
            <tr><td colspan="99">暂无记录</td></tr>
        @endforelse
    </tbody>
</table>

{{ $dataList->appends([
    'trade_no' => $tradeNo,
    'trade_type' => $tradeType,
    'time_start' => $timeStart,
    'time_end' => $timeEnd,
    ])->links() }}
@endsection

@section('js')
<script>
layui.use(['laydate', 'form'], function () {
    var laydate = layui.laydate;

    laydate.render({elem: '#time-start'});
    laydate.render({elem: '#time-end'});
});
</script>
@endsection
