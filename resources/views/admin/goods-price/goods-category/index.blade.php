@extends('layouts.admin')
@section('css')
    <style type="text/css">
        #master-table .table-cut-off {
            border-right-width: 5px;
        }
    </style>
@endsection

@section('content')
    <form class="form-inline">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="分类名称" name="name" value="{{ Request::input('name') }}">
        </div>

        <button type="submit" class="btn btn-primary">查询</button>
        <button type="button" class="btn btn-success" id="add-new">新增</button>
    </form>

    <table class="table table-bordered table-hover m-t" id="master-table">
        <thead>
        <tr>
            <th rowspan="2">分类编码</th>
            <th rowspan="2">分类名称</th>
            <th rowspan="2">排序</th>
            <th rowspan="2">商品类型</th>
            <th rowspan="2">状态</th>
            <th rowspan="2">用户日限量</th>
            <th rowspan="2">用户日限额</th>
            <th rowspan="2">前台智能选择</th>
            <th rowspan="2">创建时间</th>
            <th rowspan="2">最后更新</th>
            <th rowspan="2" class="table-cut-off">操作</th>
            <th colspan="2">充值必填项</th>
        </tr>
        <tr>
            <th>勾选选项</th>
            <th>导入区服</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($dataList as $data)
            <tr>
                <td>{{ $data->no }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->sortord }}</td>
                <td>{{ $type[$data->type] }}</td>
                <td>{{ $GLOBALS['status'][$data->status] }}</td>
                <td>{{ $data->user_max_quantity ?: '不限' }}</td>
                <td>{{ $data->user_max_parvalue ?: '不限' }}</td>
                <td>{{ $data->show_quick_choose ? '显示' : '隐藏' }}</td>
                <td>{{ $data->created_at }}</td>
                <td>{{ $data->updated_at }}</td>
                <td class="table-cut-off">
                    <button class="btn btn-warning btn-xs edit"
                            data-show="{{ route('admin.goods.category.show', $data->id) }}"
                            data-update="{{ route('admin.goods.category.update', $data->id) }}">编辑
                    </button>
                    <button class="btn btn-info btn-xs icon" data-url="{{ route('admin.goods.category.icons', $data->id) }}">图标</button>
                </td>

                <td>
                    @if (in_array($data->type, [3, 5, 6]))
                        --
                    @else
                        <form>
                            @foreach ($templates as $template)
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="template_ids[]"
                                           value="{{ $template->id }}"
                                            {{ $data->templateIds->pluck('template_id')->contains($template->id) ? 'checked' : '' }}
                                    /> {{ $template->name }}
                                </label>
                            @endforeach
                            <button type="button" class="btn btn-success btn-xs update-template"
                                    data-url="{{ route('admin.goods.category.update-templates', $data->id) }}">修改
                            </button>
                        </form>
                    @endif
                </td>
                <td>
                    @if ($data->type == 3)
                        --
                    @else
                        <button class="btn btn-success btn-xs region-server"
                                data-url="{{ route('admin.goods.category.region-server', $data->id) }}">区服
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

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
                            <label for="no" class="col-sm-3 control-label">分类编码</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="no" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">分类名称</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="name" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="type" class="col-sm-3 control-label">类型</label>
                            <div class="col-sm-9">
                                <label class="radio-inline"><input type="radio" name="type" value="3" checked>
                                    卡密</label>
                                <label class="radio-inline"><input type="radio" name="type" value="4"> 直充</label>
                                <label class="radio-inline"><input type="radio" name="type" value="5"> 代金券</label>
                                <label class="radio-inline"><input type="radio" name="type" value="6"> 兑换券</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status" class="col-sm-3 control-label">状态</label>
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="1" checked> 正常
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="0"> 禁用
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="show_quick_choose" class="col-sm-3 control-label">前台智能选择</label>
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input type="radio" name="show_quick_choose" value="1" checked> 显示
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="show_quick_choose" value="0"> 隐藏
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="user_max_quantity" class="col-sm-3 control-label">用户日限量</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="user_max_quantity" placeholder="正整数，0表示无限量"
                                       autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="user_max_parvalue" class="col-sm-3 control-label">用户日限额</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="user_max_parvalue" placeholder="正整数，0表示无限额"
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sortord" class="col-sm-3 control-label">排序</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="sortord" placeholder="填整数，越小越靠前，可以为负数"
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="desc" class="col-sm-3 control-label">商品描述</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="desc" placeholder="6万字以内，右下角可以拖拽。"
                                       autocomplete="off"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pay_warning" class="col-sm-3 control-label">支付警告</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="pay_warning" placeholder="6万字以内，右下角可以拖拽。"
                                       autocomplete="off"></textarea>
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
            $('#myModalLabel').text('新增');
            $('#no').val('');
            $('#name').val('');
            $('#sortord').val('');
            $('#user_max_quantity').val('');
            $('#user_max_parvalue').val('');
            $('[name="status"][value="1"]').prop('checked', true);
            $('[name="type"][value="1"]').prop('checked', true);
            $('[name="show_quick_choose"][value="1"]').prop('checked', true);
            $('#desc').val('');
            $('#pay_warning').val('');
            $('#data-form').attr('action', "{{ route('admin.goods.category.store') }}").attr('method', 'POST');
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

                $('#no').val(data.contents.no);
                $('#name').val(data.contents.name);
                $('#sortord').val(data.contents.sortord);
                $('#user_max_quantity').val(data.contents.user_max_quantity);
                $('#user_max_parvalue').val(data.contents.user_max_parvalue);
                $('[name="status"][value="' + data.contents.status + '"]').prop('checked', true);
                $('[name="type"][value="' + data.contents.type + '"]').prop('checked', true);
                $('[name="show_quick_choose"][value="' + data.contents.show_quick_choose + '"]').prop('checked', true);
                $('#desc').val(data.contents.desc);
                $('#pay_warning').val(data.contents.pay_warning);
                $('#myModal').modal();
            }, 'json');
        });

        // 提交
        $('#submit-data-form').click(function () {
            var load = layer.load(0, {shade: 0.2});

            $.ajax({
                url: $('#data-form').attr('action'),
                type: 'POST',
                dataType: 'json',
                data: {
                    no: $('#no').val(),
                    name: $('#name').val(),
                    sortord: $('#sortord').val(),
                    user_max_quantity: $('#user_max_quantity').val(),
                    user_max_parvalue: $('#user_max_parvalue').val(),
                    status: $('[name="status"]:checked').val(),
                    type: $('[name="type"]:checked').val(),
                    desc: $('#desc').val(),
                    pay_warning: $('#pay_warning').val(),
                    show_quick_choose: $('[name="show_quick_choose"]:checked').val(),
                    _method: $('#data-form').attr('method')
                },
                error: function (data) {
                    layer.close(load);
                    errors = data.responseJSON.errors;
                    for (key in errors) {
                        layer.alert(errors[key][0], {icon: 5});
                        return false;
                    }
                },
                success: function (data) {
                    if (data.status == 1) {
                        window.location.reload();
                    } else {
                        layer.close(load);
                        layer.alert(data.message, {icon: 5});
                    }
                }
            });
        });

        // 修改参数模板
        $('.update-template').click(function () {
            var load = layer.load(0, {shade: 0.2});
            $.post($(this).data('url'), $(this).parent().serialize(), function (data) {
                layer.close(load);
                if (data.status) {
                    layer.msg('修改成功');
                } else {
                    layer.msg(data.message);
                }
            }, 'json');
        });

        // 编辑区服
        $('.region-server').click(function () {
            layer.open({
                type: 2,
                title: '区服管理',
                shadeClose: true,
                shade: 0.5,
                area: ['1000px', '90%'],
                content: $(this).data('url')
            });
        });

        // 编辑图标
        $('.icon').click(function () {
            layer.open({
                type: 2,
                title: '图标管理',
                shadeClose: true,
                shade: 0.5,
                area: ['1000px', '90%'],
                content: $(this).data('url')
            });
        });
    </script>
@endsection
