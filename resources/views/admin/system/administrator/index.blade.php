@extends('admin.layouts.base')
@section('breadcrumb')
<li>系统管理</li>
@parent
@endsection
@section('title', '管理员管理')

@section('content')
<form class="form-inline">
    <div class="form-group">
        <label for="channel-id">账号：</label>
        <input type="text" class="form-control" name="name" value="{{ Request::input('name') }}">
    </div>

    <button type="submit" class="btn btn-primary">查询</button>
    <button type="button" class="btn btn-success" id="add-new" data-toggle="modal"
        data-target="#add-new-modal">新增</button>
</form>

<table class="table table-striped table-condensed m-t">
    <tr>
        <th>ID</th>
        <th>账号</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>最后更新</th>
        <th>操作</th>
    </tr>
    @foreach ($dataList as $data)
    <tr>
        <td>{{ $data->id }}</td>
        <td>{{ $data->name }}</td>
        <td>{{ $data->deleted_at ? '禁用' : '正常' }}</td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>
        <td>
            @if ($data->deleted_at)
            <button class="btn btn-success btn-xs restore"
                data-url="{{ route('admin.system.administrator.restore', $data->id) }}">启用</button>
            @else
            <button class="btn btn-danger btn-xs destroy"
                data-url="{{ route('admin.system.administrator.destroy', $data->id) }}">禁用</button>
            @endif

            <button class="btn btn-info btn-xs edit"
                data-url="{{ route('admin.system.administrator.update', $data->id) }}">密码</button>
            <button class="btn btn-warning btn-xs role"
                data-url="{{ route('admin.system.administrator.update-roles', $data->id) }}">角色</button>
        </td>
    </tr>
    @endforeach
</table>

{{ $dataList->appends(Request::all())->links() }}

<!-- Modal -->
<div class="modal fade" id="add-new-modal" tabindex="-1" role="dialog" aria-labelledby="add-new-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="add-new-label"></h4>
            </div>
            <div class="modal-body" style="padding-right: 60px;">
                <form class="form-horizontal" id="data-form" method="">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">账号</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">密码</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="password">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit-admin">提交</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>

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
    // 提交
    $('#submit-admin').click(function () {
        buer_post("{{ route('admin.system.administrator.store') }}", {
            name: $('#name').val(),
            password: $('#password').val()
        }, false);
    });

    // 禁用
    $('.destroy').click(function () {
        buer_post($(this).data('url'), {_method: 'DELETE'}, false);
    });

    // 启用
    $('.restore').click(function () {
        buer_post($(this).data('url'), {_method: 'PATCH'}, false);
    });

    // 修改密码
    $('.edit').click(function () {
        var url = $(this).data('url');

        layer.prompt({
            title: '请输入新密码'
        }, function (value, index, elem) {
            layer.close(index);
            buer_post(url, {password: value, _method: 'PATCH'});
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
</script>
@endsection
