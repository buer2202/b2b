@extends('admin.layouts.base')

@section('content')
<form class="form-inline" id="data-form">
    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        <input type="text" class="form-control" id="start-time" name="start_time"
            value="{{ Request::input('start_time') }}" placeholder="开始时间" readonly>
    </div>

    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        <input type="text" class="form-control" id="end-time" name="end_time" value="{{ Request::input('end_time') }}"
            placeholder="结束时间" readonly>
    </div>

    <div class="form-group">
        <label for="no">加款单号：</label>
        <input type="text" class="form-control" name="no" value="{{ Request::input('no') }}">
    </div>
    &nbsp;
    <div class="form-group">
        <label for="user-id">用户ID：</label>
        <input type="text" class="form-control" name="user_id" value="{{ Request::input('user_id') }}">
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
    <button type="submit" class="btn btn-primary">查询</button>
    <button class="btn btn-default" type="button" id="export-flow">导出</button>
    <button type="button" class="btn btn-success" id="add-new" data-toggle="modal" data-target="#myModal">线下加款</button>
</form>

<table class="table table-striped table-condensed m-t">
    <tr>
        <th>单号</th>
        <th>外部单号</th>
        <th>用户ID</th>
        <th>支付类型</th>
        <th>收款账号</th>
        <th>金额</th>
        <th>状态</th>
        <th>备注</th>
        <th>创建人</th>
        <th>创建时间</th>
        <th>审核人</th>
        <th>审核时间</th>
        <th>最后更新</th>
        <th width="100">操作</th>
    </tr>
    @forelse ($dataList as $data)
    <tr>
        <td>{{ $data->no }}</td>
        <td>{{ $data->external_order_id }}</td>
        <td>{{ $data->user_id }}</td>
        <td>{{ $status['pay_type'][$data->pay_type] }}</td>
        <td>{{ $data->receive_account }}</td>
        <td>{{ $data->fee }}</td>
        <td>{{ $status['status'][$data->status] }}</td>
        <td>{{ $data->remark }}</td>
        <td>{{ $data->created_by }}</td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->auditd_by ?: '--' }}</td>
        <td>{{ $data->auditd_at ?: '--' }}</td>
        <td>{{ $data->updated_at }}</td>
        <td>
            @if ($data->status == 1)
            <button class="btn btn-success btn-xs agree"
                data-url="{{ route('admin.finance.user-add-money.agree', $data->id) }}">审核</button>
            <button class="btn btn-danger btn-xs refuse"
                data-url="{{ route('admin.finance.user-add-money.refuse', $data->id) }}">拒绝</button>
            @else
            --
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="99">暂无数据</td>
    </tr>
    @endforelse
</table>

{{ $dataList->appends(Request::all())->links() }}

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="user-id" class="col-sm-2 control-label">用户ID</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="user-id">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fee" class="col-sm-2 control-label">加款金额</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="fee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="remark" class="col-sm-2 control-label">备注</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="remark">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit-data-form">提交</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
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

    $('#export-flow').click(function () {
        var url = "{{ route('admin.finance.user-add-money.export') }}?" + $('#data-form').serialize();
        window.location.href = url;
    });

    // 加款
    $('#submit-data-form').click(function () {
        buer_post("{{ route('admin.finance.user-add-money.store') }}", {
            user_id: $('#user-id').val(),
            fee: $('#fee').val(),
            remark: $('#remark').val()
        });
    });

    // 同意, 拒绝
    $('.agree, .refuse').click(function () {
        var url = $(this).data('url');
        layer.confirm('再次确认', function (data) {
            buer_post(url);
        });
    });
</script>
@endsection
