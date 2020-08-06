@extends('layouts.admin')
@section('css')
    <style type="text/css">
        #my-table th {vertical-align:middle;}
    </style>
@endsection

@section('content')
    <form class="form-inline">
        <div class="form-group">
            <select class="form-control" name="category_id">
                <option value="">所有分类</option>
                @foreach ($categories as $value)
                    <option value="{{ $value->id }}" {{ $value->id == request('category_id') ? 'selected' : '' }}>{{ $value->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="FOP商品ID" name="km_goods_id" value="{{ Request::input('km_goods_id') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="商品名称" name="name" value="{{ Request::input('name') }}">
        </div>

        <button type="submit" class="btn btn-primary">查询</button>
        <button type="button" class="btn btn-success" id="add-new">新增</button>
    </form>

    <table class="table table-bordered table-condensedz table-hover m-t" id="my-table">
        <tr>
            <th rowspan="2">分类编码</th>
            <th rowspan="2">分类名称</th>
            <th rowspan="2">实际名称</th>
            <th rowspan="2">实际面值</th>
            <th rowspan="2">状态</th>
            <th colspan="7">开放平台商品资料</th>
            <th rowspan="2">创建时间</th>
            <th rowspan="2">最后更新</th>
            <th rowspan="2">操作</th>
        </tr>
        <tr>
            <th>商品ID</th>
            <th>FOP商品名称</th>
            <th>商品面值</th>
            <th>库存类型</th>
            <th>售价</th>
            <th>库存状态</th>
            <th>销售状态</th>
        </tr>
        @foreach ($dataList as $data)
            <tr>
                <td>{{ $data->category->no }}</td>
                <td>{{ $data->category->name }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ (float)$data->parvalue }}</td>
                <td style="{{ $data->status ? '' : 'background-color: #ffbab9' }}">{{ $GLOBALS['status'][$data->status] }}</td>
                <td>{{ $data->km_goods_id }}</td>
                <td>{{ $data->fop_name }}</td>
                <td>{{ (float)$data->fop_face_value }}</td>
                <td>{{ $data->product_type }}</td>
                <td>{{ (float)$data->purchase_price }}</td>
                <td>{{ $data->stock_status }}</td>
                <td>{{ $data->sales_status }}</td>
                <td>{{ $data->created_at }}</td>
                <td>{{ $data->updated_at }}</td>
                <td>
                    <button class="btn btn-warning btn-xs edit"
                            data-show="{{ route('admin.goods.km-goods.show', $data->id) }}"
                            data-update="{{ route('admin.goods.km-goods.update', $data->id) }}">编辑
                    </button>
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
                            <label for="no" class="col-sm-3 control-label">分类编码</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="category_id">
                                    @foreach ($categories as $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="km_goods_id" class="col-sm-3 control-label">FOP商品ID</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="km_goods_id" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">实际名称</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="name" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="parvalue" class="col-sm-3 control-label">实际面值</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="parvalue" autocomplete="off">
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
            $('#km_goods_id').val('');
            $('#name').val('');
            $('#parvalue').val('');
            $('[name="status"][value="1"]').prop('checked', true);
            $('#data-form').attr('action', "{{ route('admin.goods.km-goods.store') }}").attr('method', 'POST');
            $('#myModal').modal();
        });

        // 编辑
        $('.edit').click(function () {
            $('#data-form').attr('action', $(this).data('update')).attr('method', 'PUT');
            $('#myModalLabel').text('编辑');

            $.get($(this).data('show'), function (data) {
                if (data.status != 1) {
                    layer.alert(data.message);
                    return false;
                }

                $('#category_id').val(data.contents.category_id);
                $('#km_goods_id').val(data.contents.km_goods_id);
                $('#name').val(data.contents.name);
                $('#parvalue').val(data.contents.parvalue);
                $('[name="status"][value="' + data.contents.status + '"]').prop('checked', true);
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
                    category_id: $('#category_id').val(),
                    km_goods_id: $('#km_goods_id').val(),
                    name: $('#name').val(),
                    parvalue: $('#parvalue').val(),
                    status: $('[name="status"]:checked').val(),
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
    </script>
@endsection
