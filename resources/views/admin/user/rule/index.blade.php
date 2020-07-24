@extends('admin.layouts.base')

@section('content')
<form class="form-inline">
    <div class="form-group">
        <label>权限名：</label>
        <input type="text" class="form-control" name="title" value="{{ Request::input('title') }}">
    </div>
    &nbsp;
    <div class="form-group">
        <label>分组名：</label>
        <input type="text" class="form-control" name="group_name" value="{{ Request::input('group_name') }}">
    </div>

    <button type="submit" class="btn btn-primary">查询</button>
    <button type="button" class="btn btn-success" id="add-new">新增</button>
</form>

<table class="table table-striped table-condensed m-t">
    <tr>
        <th>编号</th>
        <th>权限</th>
        <th>权限名</th>
        <th>状态</th>
        <th>分组</th>
        <th>分组名</th>
        <th>菜单显示</th>
        <th>菜单可点</th>
        <th>排序</th>
        <th>创建时间</th>
        <th>最后更新</th>
        <th>操作</th>
    </tr>
    @foreach ($dataList as $data)
    <tr style="{{ $data->menu_show ? 'font-weight:bold;' : '' }}">
        <td>{{ $data->id }}</td>
        <td>{{ $data->name }}</td>
        <td>{{ $data->title }}</td>
        <td>{{ $GLOBALS['status'][$data->status] }}</td>
        <td>{{ $data->group }}</td>
        <td>{{ $data->group_name }}</td>
        <td>{{ $GLOBALS['on_off'][$data->menu_show] }}</td>
        <td>{{ $GLOBALS['on_off'][$data->menu_click] }}</td>
        <td>{{ $data->sortord }}</td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>
        <td>
            <button class="btn btn-warning btn-xs edit" data-show="{{ route('admin.user.rule.show', $data->id) }}"
                data-update="{{ route('admin.user.rule.update', $data->id) }}">编辑</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="data-form" method="">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">权限</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" placeholder="填路由命名。例：home.user.rule.index"
                                autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">权限名</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title" placeholder="填权限中文名" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="group" class="col-sm-2 control-label">分组</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="group" placeholder="填分组名，路由的第二段。例：user">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="group_name" class="col-sm-2 control-label">分组名</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="group_name" placeholder="填分组中文名">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="col-sm-2 control-label">状态</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" name="status" value="1" checked> 正常
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="0"> 禁用
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="menu_show" class="col-sm-2 control-label">菜单显示</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" name="menu_show" value="1"> 开启
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="menu_show" value="0" checked> 关闭
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="menu_click" class="col-sm-2 control-label">菜单可点</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" name="menu_click" value="1"> 开启
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="menu_click" value="0" checked> 关闭
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sortord" class="col-sm-2 control-label">排序</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sortord" placeholder="填整数，越小越靠前，可以为负数"
                                autocomplete="off">
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
    // 新增
    $('#add-new').click(function () {
        // 初始化表单
        $('#myModalLabel').text('新增')
        $('#name').val('');
        $('#title').val('');
        $('#group').val('');
        $('#group_name').val('');
        $('#sortord').val('');
        $('[name="status"][value="1"]').prop('checked', true);
        $('[name="menu_show"][value="0"]').prop('checked', true);
        $('[name="menu_click"][value="0"]').prop('checked', true);

        $('#data-form').attr('action', "{{ route('admin.user.rule.store') }}").attr('method', 'POST');
        $('#myModal').modal();
    });

    // 编辑
    $('.edit').click(function () {
        $('#data-form').attr('action', $(this).data('update')).attr('method', 'PUT');
        $('#myModalLabel').text('编辑')

        $.get($(this).data('show'), function (data) {
            if (data.status != 1) {
                layer.alert(data.message);
                return false;
            }

            $('#name').val(data.contents.name);
            $('#title').val(data.contents.title);
            $('#group').val(data.contents.group);
            $('#group_name').val(data.contents.group_name);
            $('#sortord').val(data.contents.sortord);
            $('[name="status"][value="' + data.contents.status + '"]').prop('checked', true);
            $('[name="menu_show"][value="' + data.contents.menu_show + '"]').prop('checked', true);
            $('[name="menu_click"][value="' + data.contents.menu_click + '"]').prop('checked', true);

            $('#myModal').modal();
        }, 'json');
    });

    // 提交
    $('#submit-data-form').click(function () {
        buer_post($('#data-form').attr('action'), {
            name: $('#name').val(),
            title: $('#title').val(),
            group: $('#group').val(),
            group_name: $('#group_name').val(),
            sortord: $('#sortord').val(),
            status: $('[name="status"]:checked').val(),
            menu_show: $('[name="menu_show"]:checked').val(),
            menu_click: $('[name="menu_click"]:checked').val(),
            _method: $('#data-form').attr('method')
        }, false);
    });
</script>
@endsection
