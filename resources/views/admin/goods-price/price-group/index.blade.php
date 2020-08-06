@extends('admin.layouts.base')

@section('content')
<form class="form-inline">
    <div class="form-group">
        <select class="form-control" name="goods_model">
            <option value="">所有业务</option>
            @foreach ($config as $key => $value)
            <option value="{{ $key }}" {{ $key == request('goods_model') ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="折扣组名" name="name" value="{{ Request::input('name') }}">
    </div>

    <button type="submit" class="btn btn-primary">查询</button>
    <button type="button" class="btn btn-success" id="add-new">新增</button>

    <div class="form-group" style="margin-left: 20px">
        <input type="text" class="form-control" placeholder="用户ID" id="user_id">
        <button type="button" class="btn btn-info" id="search-user">查找用户</button>
    </div>
</form>

<table class="table table-striped table-condensed table-hover m-t" id="my-table">
    <thead>
        <tr>
            <th>价格组ID</th>
            <th>价格组名</th>
            <th>所属业务</th>
            <th>商品模型</th>
            <th>创建时间</th>
            <th>更新时间</th>
            <th>操作</th>
        </tr>
    </thead>
    @foreach ($dataList as $data)
    <tr>
        <td>{{ $data->id }}</td>
        <td>{{ $data->name }}</td>
        <td>{{ $config[$data->goods_model] }}</td>
        <td>{{ $data->goods_model }}</td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>
        <td>
            <button class="btn btn-warning btn-xs edit-price-group-name"
                data-url="{{ route('admin.goods-price.price-group.update', $data->id) }}">改名</button>
            <button class="btn btn-success btn-xs open-iframe"
                data-url="{{ route('admin.goods-price.price-group-goods.inside', $data->id) }}"
                data-iframe-title="组商品管理：{{ $data->id }} - {{ $data->name }}">密价</button>
            <button class="btn btn-info btn-xs open-iframe"
                data-url="{{ route('admin.goods-price.price-group-user.inside', $data->id) }}"
                data-iframe-title="组用户管理：{{ $data->id }} - {{ $data->name }}">用户</button>
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
                <h4 class="modal-title" id="myModalLabel">新增组</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="data-form">
                    <div class="form-group">
                        <label for="goods_model" class="col-sm-3 control-label">所属业务</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="goods_model">
                                @foreach ($config as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">价格组名</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="name" autocomplete="off">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="add-price-group">添加</button>
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
        // 字段
        $('#name').val('');
        $('#myModal').modal();
    });

    // 添加
    $('#add-price-group').click(function () {
        buer_post("{{ route('admin.goods-price.price-group.store') }}", {
            goods_model: $('#goods_model').val(),
            name: $('#name').val()
        }, false);
    });

    // 修改组名
    $('.edit-price-group-name').click(function () {
        var url = $(this).data('url');

        layer.prompt({
            title: '请输入新价格组名'
        }, function (value) {
            buer_post(url, {
                name: value,
                '_method': 'patch'
            });
        });
    });

    // iframe弹窗
    $('.open-iframe').click(function () {
        layer.open({
            type: 2,
            title: $(this).data('iframe-title'),
            shadeClose: true,
            shade: 0.2,
            area: ['90%', '90%'],
            content: $(this).data('url')
        });
    });

    // 快速查找用户
    $('#search-user').click(function () {
        var load = layer.load(0, {shade: 0.3});

        $.get("{{ route('admin.goods-price.price-group.search-user') }}", {
            goods_model: $('[name="goods_model"]').val(),
            user_id: $('#user_id').val()
        }, function (data) {
            layer.close(load);
            if (data.status) {
                layer.open({
                    type: 2,
                    title: '组用户管理：' + data.contents.group_id + ' - ' + data.contents.group_name,
                    shadeClose: true,
                    shade: 0.2,
                    area: ['90%', '90%'],
                    content: data.contents.url
                });
            } else {
                layer.alert(data.message, {
                    icon: 5
                });
            }
        }, 'json');
    });
</script>
@endsection
