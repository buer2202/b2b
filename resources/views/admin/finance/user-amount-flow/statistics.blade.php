@extends('admin.layouts.base')

@section('content')
<form class="form-inline" id="inquiry-from">
    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        <input type="text" class="form-control" id="start-time" name="start_time" value="{{ $startTime }}" placeholder="开始时间" readonly>
    </div>

    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        <input type="text" class="form-control" id="end-time" name="end_time" value="{{ $endTime }}" placeholder="结束时间" readonly>
    </div>

    <div class="form-group">
        <input type="text" class="form-control" placeholder="用户ID" name="user_id" value="{{ Request::input('user_id') }}">
    </div>

    <button class="btn btn-primary" type="submit">查询</button>
</form>

<table class="table table-bordered table-condensed m-t">
    <thead>
        <tr>
            <th colspan="99">用户资金统计表 ({{ $startTime }} ~ {{ $endTime }})</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>类型</th>
            <th>子类型</th>
            <th>总金额</th>
        </tr>

        @foreach ($dataList as $data)
            <tr>
                <td>{{ $data->trade_type }}.{{ $tradeType['user'][$data->trade_type] }}</td>
                <td>{{ $data->trade_subtype }}.{{ $tradeType['user_sub'][$data->trade_subtype] }}</td>
                <td>{{ $data->total_fee }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('js')
<script>
var option = {
    language: 'zh-CN',
    format: 'yyyy-mm-dd',
    minView: 2,
    autoclose: true,
    todayBtn: true,
    todayHighlight: true
};

$('#start-time').datetimepicker(option);
$('#end-time').datetimepicker(option);
</script>
@endsection
