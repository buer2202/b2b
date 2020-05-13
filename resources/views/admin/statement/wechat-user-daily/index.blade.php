@extends('admin.layouts.base')

@section('content')
<form class="form-inline" id="data-form">
    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        <input type="text" class="form-control" id="start-time" name="start_time" value="{{ Request::input('start_time') }}" placeholder="开始时间" readonly>
    </div>

    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        <input type="text" class="form-control" id="end-time" name="end_time" value="{{ Request::input('end_time') }}" placeholder="结束时间" readonly>
    </div>

    <button class="btn btn-primary" type="submit">查询</button>
</form>

<table class="table table-striped table-condensed m-t">
    <thead>
        <tr>
            <th>日期</th>
            <th>当日支付用户数</th>
            <th>新增微信用户数</th>
            <th>微信用户总数</th>
            <th>新增平台用户数</th>
            <th>平台用户总数</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataList as $data)
            <tr>
                <td>{{ $data->pay_date }}</td>
                <td>{{ (float)$data->wechat_user_quantity }}</td>
                <td>{{ (float)$data->new_wechat_user_quantity }}</td>
                <td>{{ (float)$data->total_wechat_user_quantity }}</td>
                <td>{{ (float)$data->new_user_quantity }}</td>
                <td>{{ (float)$data->total_user_quantity }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $dataList->appends(Request::all())->links() }}
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
