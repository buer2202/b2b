@extends('admin.layouts.iframe')
@section('css')
<style type="text/css">
    .row .panel-body {
        padding: 0 10px;
    }

    .user-box {
        padding: 0 5px;
    }
</style>
@endsection

@section('content')
<form>
    <div class="input-group" style="float: left; width: 300px;">
        <input type="text" class="form-control" name="search_key" placeholder="用户ID，真实姓名，备注"
            value="{{ request('search_key') }}">
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">查询</button>
        </span>
    </div>

    <button style="margin-left: 20px;" class="btn btn-default" type="button" id="select-all">全选</button>
    <button class="btn btn-default btn-success" type="button" id="add">添加到组 - {{ $group->name }}</button>
</form>

<hr />

<form id="form-discount-users" style="height: 500px; overflow-x: hidden;overflow-y: auto; padding: 0 10px;">
    @foreach ($dataList->chunk(6) as $chunk)
    <div class="row">
        @foreach ($chunk as $user)
        <div class="col-md-2 user-box">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="users[]" value="{{ $user->id }}" />
                            <div>{{ $user->id }}</div>
                            <div class="text-primary">{{ $user->real_name ?: '--' }}</div>
                            <div class="text-info">{{ $user->remark ?: '--' }}</div>
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
        $('[name="users[]"]').prop('checked', true);
    });

    $('#add').click(function () {
        layer.alert('将勾选用户添加到组：{{ $group->name }}', function () {
            buer_post("{{ route('admin.goods-price.price-group-user.add', $group->id) }}", $(
                '#form-discount-users').serialize(), function (data, load) {
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
