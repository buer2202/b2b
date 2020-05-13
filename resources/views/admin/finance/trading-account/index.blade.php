@extends('admin.layouts.base')

@section('content')
<form class="form-inline">
    <div class="form-group">
        <label for="user-id">用户ID：</label>
        <input type="text" class="form-control" id="user-id" name="user_id" value="{{ Request::input('user_id') }}">
    </div>
    &nbsp;
    <div class="form-group">
        <label for="bank-card-no">账号：</label>
        <input type="bank_card_no" class="form-control" id="bank-card-no" name="bank_card_no" value="{{ Request::input('bank_card_no') }}">
    </div>

    <button type="submit" class="btn btn-primary">查询</button>
</form>

<table class="table table-striped table-condensed m-t">
    <tr>
        <th>用户ID</th>
        <th>类型</th>
        <th>托管单位</th>
        <th>收款账号</th>
        <th>收款人姓名</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>最后更新</th>
        <th style="width: 150px">操作</th>
    </tr>
    @foreach ($dataList as $data)
        <tr>
            <td>{{ $data->user_id }}</td>
            <td>{{ $userTradingAccount['type'][$data->type] }}</td>
            <td>{{ $data->trustee }}</td>
            <td>{{ $data->account }}</td>
            <td>{{ $data->name }}</td>
            <td>{{ $userTradingAccount['status'][$data->status] }}</td>
            <td>{{ $data->created_at }}</td>
            <td>{{ $data->updated_at }}</td>
            <td>
                <button class="btn btn-info btn-xs destroy" data-url="{{ route('admin.finance.trading-account.destroy', $data->id) }}">删除</button>
            </td>
        </tr>
    @endforeach
</table>

{{ $dataList->appends(Request::all())->links() }}

@endsection

@section('js')
<script>
function initialize() {
    $('#form-user-id').val('');
    $('#form-bank').val('');
    $('#form-bank-card-no').val('');
}

// 删除
$('.destroy').click(function () {
    var url = $(this).data('url');

    layer.confirm('确定删除', function () {
        var loading = layer.load(0, {shade: 0.2});

        $.post(url, {
            '_method': 'DELETE'
        }, function (data) {
            layer.close(loading);

            if (data.status == 1) {
                window.location.reload();
            } else {
                layer.alert(data.message, {icon: 5});
            }
        });
    });
});
</script>
@endsection
