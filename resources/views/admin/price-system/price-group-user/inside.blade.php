@extends('layouts.admin')
@section('nav')
<div>&nbsp;</div>
@endsection
@section('crumbs', '')
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

    <div class="pull-left" style="margin-right: 20px">
        <button style="margin-left: 20px;" class="btn btn-default btn-success" type="button" id="add">新增</button>
        <button style="margin-left: 20px;" class="btn btn-default" type="button" id="select-all">全选</button>
        <button class="btn btn-default btn-danger" type="button" id="delete">删除</button>
    </div>

    <div class="input-group" class="pull-left">
        <select class="form-control" id="move">
            <option value="move">移动到...</option>
            @foreach ($bizGroups as $g)
            <option value="{{ $g->id }}">{{ $g->id }}.{{ $g->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="clearfix"></div>
</form>

<hr />

<form id="form-discount-users" style="height:600px;overflow-x:hidden;overflow-y:auto;padding:0 10px;">
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
    $('#add').click(function () {
        layer.open({
            type: 2,
            title: '添加组用户 - {{ $group->name }}',
            shadeClose: true,
            shade: 0.2,
            area: ['95%', '95%'],
            content: "{{ route('admin.price-system.price-group-user.outside', $group->id) }}"
        });
    });

    // 全选
    $('#select-all').click(function () {
        $('[name="users[]"]').prop('checked', true);
    });

    // 删除
    $('#delete').click(function () {
        layer.alert('再次确认', function () {
            buer_post("{{ route('admin.price-system.price-group-user.delete', $group->id) }}", $(
                '#form-discount-users').serialize());
        });
    });

    // 移动
    $('#move').change(function () {
        var groupId = $(this).val();
        if (groupId == 'move') {
            return false;
        }

        layer.alert('确认移动到组 ' + groupId + ' 吗？', function () {
            buer_post("{{ route('admin.price-system.price-group-user.move', $group->id) }}",
                'new_group_id=' + groupId + '&' +
                $('#form-discount-users').serialize());
        });
    });
</script>
@endsection
