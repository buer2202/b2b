@extends('admin.layouts.base')

@section('content')
<form class="form-inline">
    <div class="form-group">
        <input type="text" class="form-control" id="user-id" name="user_id" value="{{ request('user_id') }}"
            placeholder="用户ID">
    </div>
    &nbsp;
    <div class="form-group">
        <input type="email" class="form-control" id="email" name="email" value="{{ request('email') }}"
            placeholder="邮箱">
    </div>
    &nbsp;
    <div class="form-group">
        <input type="name" class="form-control" id="name" name="name" value="{{ request('name') }}" placeholder="名字">
    </div>
    &nbsp;
    <div class="form-group">
        <input type="remark" class="form-control" id="remark" name="remark" value="{{ request('remark') }}"
            placeholder="备注">
    </div>
    &nbsp;

    <button type="submit" class="btn btn-primary">查询</button>
</form>

<table class="table table-striped table-condensed m-t">
    <tr>
        <th>用户ID</th>
        <th>E-Mail</th>
        <th>名字</th>
        <th>状态</th>
        <th>注册时间</th>
        <th>最后更新</th>
        <th>操作</th>
    </tr>
    @foreach ($dataList as $data)
    <tr>
        <td>{{ $data->id }}</td>
        <td>{{ $data->email }}</td>
        <td>{{ $data->name }}</td>
        <td>
            @if ($data->status)
            <button class="btn btn-success btn-xs status" data-url="{{ route('admin.user.index.status', $data->id) }}"
                data-status="0">正常
            </button>
            @else
            <button class="btn btn-danger btn-xs status" data-url="{{ route('admin.user.index.status', $data->id) }}"
                data-status="1">已禁用
            </button>
            @endif
        </td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>
    </tr>
    @endforeach
</table>

{{ $dataList->appends(Request::all())->links() }}
@endsection

@section('js')
<script>
    // 状态
    $('.status').click(function () {
        var load = layer.load(0, {
            shade: 0.3
        });

        $.ajax({
            url: $(this).data('url'),
            type: 'POST',
            dataType: 'json',
            data: {
                status: $(this).data('status'),
                _method: 'PATCH'
            },
            error: function (data) {
                layer.close(load);
                errors = data.responseJSON.errors;
                for (key in errors) {
                    layer.alert(errors[key][0], {
                        icon: 5
                    });
                    return false;
                }
            },
            success: function (data) {
                if (data.status == 1) {
                    window.location.reload();
                } else {
                    layer.close(load);
                    layer.alert(data.message, {
                        icon: 5
                    });
                }
            }
        });
    });

    // 备注
    $('.remark').click(function () {
        var url = $(this).data('url');

        layer.prompt({
            title: '请输入备注'
        }, function (value) {
            layer.load(0, {
                shade: 0.3
            });

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    remark: value,
                    _method: 'PATCH'
                },
                error: function (data) {
                    layer.closeAll();
                    errors = data.responseJSON.errors;
                    for (key in errors) {
                        layer.alert(errors[key][0], {
                            icon: 5
                        });
                        return false;
                    }
                },
                success: function (data) {
                    layer.closeAll();
                    if (data.status == 1) {
                        layer.alert('操作成功', {
                            icon: 6
                        }, function () {
                            window.location.reload();
                        });
                    } else {
                        layer.alert(data.message, {
                            icon: 5
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
