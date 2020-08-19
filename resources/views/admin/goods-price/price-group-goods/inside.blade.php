@extends('admin.layouts.iframe')

@section('content')
<form>
    <div class="input-group" style="float: left; width: 300px;">
        <input type="text" class="form-control" name="goods_name" placeholder="商品名称"
            value="{{ request('goods_name') }}">
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">查询</button>
        </span>
    </div>

    <div class="pull-left" style="margin-right: 20px">
        <button style="margin-left: 20px;" class="btn btn-default btn-success" type="button" id="add">新增</button>
    </div>

    <div class="clearfix"></div>
</form>

<hr />

<table class="table table-striped table-condensed table-hover m-t">
    <thead>
        <tr>
            <th>商品ID</th>
            <th>商品名</th>
            <th>售价组名</th>
            <th>进货价</th>
            <th>销售价</th>
            <th>操作</th>
        </tr>
    </thead>
    @foreach ($dataList as $data)
    <tr>
        <td>{{ $data->id }}</td>
        <td>{{ $data->name }}</td>
        <td>（{{ $config[$group->goods_model] }}）{{ $group->name }}</td>
        <td>{{ (float)$data->pivot->cost_price ?: '未上架' }}</td>
        <td>{{ (float)$data->pivot->sales_price ?: '未上架' }}</td>
        <td>
            <button type="button" class="btn btn-success btn-xs edit-price" data-name="{{ $group->name }}"
                data-url="{{ route('admin.goods-price.price-group-goods.edit-price', $data->pivot->id) }}">变价</button>
        </td>
    </tr>
    @endforeach
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
                        <label for="name" class="col-sm-3 control-label">商品名称：</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="name" autocomplete="off" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cost_price" class="col-sm-3 control-label">进货价：</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="cost_price" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sales_price" class="col-sm-3 control-label">销售价：</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="sales_price" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sales_price" class="col-sm-3 control-label"></label>
                        <div class="col-sm-7 text-danger">
                            价格设为0表示不销售
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
    $('#add').click(function () {
        layer.open({
            type: 2,
            title: '添加组商品 - {{ $group->name }}',
            shadeClose: true,
            shade: 0.2,
            area: ['95%', '95%'],
            content: "{{ route('admin.goods-price.price-group-goods.outside', $group->id) }}"
        });
    });

    // 编辑售价
    $('.edit-price').click(function () {
        $('#data-form').attr('action', $(this).data('url'));
        $('#name').val($(this).data('name'));
        var load = layer.load(0, {shade: 0.3});

        $.get($(this).data('url'), function (data) {
            layer.close(load);
            if (data.status) {
                $('#cost_price').val(parseFloat(data.contents.cost_price));
                $('#sales_price').val(parseFloat(data.contents.sales_price));
                $('#myModal').modal();
            } else {
                layer.alert(data.message, {icon: 5});
            }
        }, 'json');
    });

    // 变价
    $('#submit-data-form').click(function () {
        buer_post($('#data-form').attr('action'), {
            cost_price: $('#cost_price').val(),
            sales_price: $('#sales_price').val()
        }, false);
    });
</script>
@endsection
