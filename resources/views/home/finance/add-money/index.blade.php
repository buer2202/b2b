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
                @foreach ($status['status'] as $key => $value)
                    <option value="{{ $key }}" {{ $key == Request::input('status') ? 'selected' : '' }}>{{ $key }}. {{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="layui-input-inline" style="width: 400px;">
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
            <th>加款单号</th>
            <th>外部单号</th>
            <th>加款方式</th>
            <th>加款金额</th>
            <th>状态</th>
            <th>备注</th>
            <th>创建时间</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dataList as $data)
            <tr>
                <td>{{ $data->no }}</td>
                <td>{{ $data->external_order_id }}</td>
                <td>{{ $status['pay_type'][$data->pay_type] }}</td>
                <td>{{ $data->fee }}</td>
                <td>{{ $status['status'][$data->status] }}</td>
                <td>{{ $data->remark }}</td>
                <td>{{ $data->created_at }}</td>
            </tr>
        @empty
            <tr><td colspan="99">暂无记录</td></tr>
        @endforelse
    </tbody>
</table>

{{ $dataList->appends(Request::all())->links() }}

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
