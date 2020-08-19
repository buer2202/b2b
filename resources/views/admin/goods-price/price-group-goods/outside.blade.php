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

    <button style="margin-left: 20px;" class="btn btn-default" type="button" id="select-all">全选</button>
    <button class="btn btn-default btn-success" type="button" id="add">添加到组 - {{ $group->name }}</button>
</form>

<hr />

<form id="form-discount-goods" style="height: 500px; overflow-x: hidden;overflow-y: auto; padding: 0 10px;">
    @foreach ($dataList->chunk(4) as $chunk)
    <div class="row">
        @foreach ($chunk as $goods)
        <div class="col-md-3 user-box">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="goods[]" value="{{ $goods->id }}" />
                            <div class="text-primary">{{ $goods->name }}</div>
                            <div>面值:{{ $goods->face_value }}元（ID:{{ $goods->id }}）</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</form>
@endsection

@section('js')
<script>
    // 全选
    $('#select-all').click(function () {
        $('[name="goods[]"]').prop('checked', true);
    });

    $('#add').click(function () {
        layer.confirm('将勾选商品添加到组：{{ $group->name }}', function () {
            buer_post("{{ route('admin.goods-price.price-group-goods.add', $group->id) }}", $(
                '#form-discount-goods').serialize(), function (data, load) {
                layer.close(load);

                if (data.status) {
                    layer.alert('操作成功', {
                        icon: 6
                    }, function () {
                        parent.location.reload();
                    });
                } else {
                    layer.alert(data.message, {
                        icon: 5
                    });
                }
            });
        });
    });
</script>
@endsection
