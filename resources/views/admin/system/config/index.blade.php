@extends('admin.layouts.base')
@section('breadcrumb')
<li>系统配置管理</li>
@parent
@endsection
@section('title', '系统配置管理')

@section('content')
<table class="table table-hover table-condensed table-bordered m-t">
    <tr>
        <th>配置项</th>
        <th>配置值</th>
        <th>配置说明</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
    @foreach ($dataList as $data)
    <tr>
        <td>{{ $data->item }}</td>
        <td>{{ mb_strlen($data->value) > 50 ? mb_substr($data->value, 0, 50) . '...' : $data->value }}</td>
        <td>{{ $data->description }}</td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>
        <td><button class="btn btn-info btn-xs edit" data-item="{{ $data->item }}">编辑</button></td>
    </tr>
    @endforeach
</table>

{{ $dataList->links() }}
@endsection

@section('js')
<script>
    // 编辑
    $('.edit').click(function () {
        var load = layer.load(0, {
            shade: 0.2
        });
        var item = $(this).data('item');

        $.get("{{ route('admin.system.config.show') }}", {
            item: item
        }, function (data) {
            layer.close(load);

            if (data.status != 1) {
                layer.alert(data.message);
                return false;
            }

            layer.prompt({
                formType: 2,
                title: '请输入 ' + item + ' 的值',
                value: data.contents,
                area: ['800px', '350px'] // 自定义文本域宽高
            }, function (value, index, elem) {
                buer_post("{{ route('admin.system.config.update') }}", {
                    item: item,
                    value: value
                }, false);
            });
        }, 'json');
    });
</script>
@endsection
