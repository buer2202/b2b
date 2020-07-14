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
        <th>备注</th>
        <th>状态</th>
        <th>类型</th>
        <th>手机</th>
        <th>真实姓名</th>
        <th>身份证号</th>
        <th>企业名</th>
        <th>营业执照号</th>
        <th>注册时间</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
    @foreach ($dataList as $data)
    <tr>
        <td>{{ $data->id }}</td>
        <td>{{ $data->email }}</td>
        <td>{{ $data->remark }}</td>
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
        <td>{{ $data->type }}</td>
        <td>{{ $data->phone }}</td>
        <td>{{ $data->real_name }}</td>
        <td>{{ $data->id_number }}</td>
        <td>{{ $data->company }}</td>
        <td>{{ $data->license }}</td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>
        <td>
            @if ($data->parent_id == 0)
            <button class="btn btn-success btn-xs remark" data-url="{{ route('admin.user.index.remark', $data->id) }}">备注</button>
            @endif
            <button class="btn btn-warning btn-xs role" data-url="{{ route('admin.user.index.update-roles', $data->id) }}">角色</button>
            <button class="btn btn-danger btn-xs info" data-url="{{ route('admin.user.index.info', $data->id) }}">密钥</button>
        </td>
    </tr>
    @endforeach
</table>

{{ $dataList->appends(Request::all())->links() }}

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <form id="roles-form" action="" method="post">
                    <div class="list-group">
                        <p class="list-group-item list-group-item list-group-item-success">角色列表</p>
                        @foreach ($roles as $role)
                            <p class="list-group-item">
                                <label>
                                    <input type="checkbox" name="role_ids[]" id="{{ $role->id }}" value="{{ $role->id }}" />
                                    {{ $role->name }}
                                </label>
                            </p>
                        @endforeach
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

    // 详情
    $('.info').click(function () {
        $.get($(this).data('url'), function (data) {
            if (data.status == 1) {
                layer.alert(data.contents);
            } else {
                layer.alert(data.message, {
                    icon: 5
                });
            }
        }, 'json');
    });

    // 实名
    $('.certification').click(function () {
        var url = $(this).data('url');

        layer.open({
            type: 2,
            title: '实名认证',
            area: ['600px', '90%'],
            content: url
        });
    });

    // 角色
    $('.role').click(function () {
        $("#roles-form input[type='checkbox']").prop("checked", false); // 全不选
        $('#myModalLabel').text($(this).parent().siblings('.email').text());
        $('#roles-form').attr('action', $(this).data('url'));

        $.get($(this).data('url'), function (data) {
            var roleData = data.contents;
            if (data.contents) {
                for (var i = 0; i < roleData.length; i++) {
                    $("#" + roleData[i]).prop("checked", true);
                };
            }

            $('#myModal').modal();
        }, 'json');
    });

    // 角色修改
    $('#submit-data-form').click(function () {
        var load = layer.load(0, {
            shade: 0.2
        });

        $.post($('#roles-form').attr('action'), $('#roles-form').serialize(), function (data) {
            layer.close(load);

            if (data.status == 1) {
                layer.msg('操作成功');
            } else {
                layer.msg(data.message);
            }
        }, 'json');
    });

    // 备注
    $('.remark').click(function () {
        var url = $(this).data('url');

        layer.prompt({
            title: '请输入备注'
        }, function (value) {
            var load = layer.load(0, {
                shade: 0.3
            });

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    remark: value
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
                        layer.alert('操作成功', {
                            icon: 6
                        }, function () {
                            window.location.reload();
                        });
                    } else {
                        layer.close(load);
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
