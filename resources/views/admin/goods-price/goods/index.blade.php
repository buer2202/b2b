@extends('admin.layouts.base')
@section('css')
<style type="text/css">
    #my-table th {
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<form class="form-inline">
    <div class="form-group">
        <select class="form-control" name="category_id">
            <option value="">所有分类</option>
            @foreach ($categories as $value)
            <option value="{{ $value->id }}" {{ $value->id == request('category_id') ? 'selected' : '' }}>
                {{ $value->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="商品ID" name="goods_id"
            value="{{ Request::input('goods_id') }}">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="商品名称" name="name" value="{{ Request::input('name') }}">
    </div>

    <button type="submit" class="btn btn-primary">查询</button>
    <button type="button" class="btn btn-success" id="add-new">新增</button>
</form>

<table class="table table-striped table-condensed table-hover m-t" id="master-table">
    <tr>
        <th>商品ID</th>
        <th>商品分类</th>
        <th>商品名称</th>
        <th>商品面值</th>
        <th>商品状态</th>
        <th>创建时间</th>
        <th>最后更新</th>
        <th>操作</th>
    </tr>
    @foreach ($dataList as $data)
    <tr>
        <td>{{ $data->id }}</td>
        <td>{{ $data->goodsCategory->name }}</td>
        <td>{{ $data->name }}</td>
        <td>{{ $data->face_value }}</td>
        <td style="{{ $data->status ? '' : 'background-color: #ffbab9' }}">{{ $GLOBALS['status'][$data->status] }}</td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>
        <td>
            <button class="btn btn-warning btn-xs edit"
                data-url="{{ route('admin.goods-price.goods.update', $data->id) }}">编辑</button>
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
                        <label for="no" class="col-sm-3 control-label">商品分类</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="goods_category_id">
                                @foreach ($categories as $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">商品名称</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="face_value" class="col-sm-3 control-label">商品面值</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="face_value" autocomplete="off">
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
        $('#name').val('');
        $('#face_value').val('');
        $('[name="status"][value="1"]').prop('checked', true);
        $('#data-form').attr('action', "{{ route('admin.goods-price.goods.store') }}").attr('method', 'POST');
        $('#myModal').modal();
    });

    // 编辑
    $('.edit').click(function () {
        $('#data-form').attr('action', $(this).data('url')).attr('method', 'PUT');
        $('#myModalLabel').text('编辑');

        $.get($(this).data('url'), function (data) {
            if (data.status != 1) {
                layer.alert(data.message);
                return false;
            }

            $('#goods_category_id').val(data.contents.goods_category_id);
            $('#name').val(data.contents.name);
            $('#face_value').val(data.contents.face_value);
            $('[name="status"][value="' + data.contents.status + '"]').prop('checked', true);
            $('#myModal').modal();
        }, 'json');
    });

    // 提交
    $('#submit-data-form').click(function () {
        buer_post($('#data-form').attr('action'), {
            _method: $('#data-form').attr('method'),
            goods_category_id: $('#goods_category_id').val(),
            name: $('#name').val(),
            face_value: $('#face_value').val(),
            status: $('[name="status"]:checked').val()
        }, false);
    });
</script>
@endsection
