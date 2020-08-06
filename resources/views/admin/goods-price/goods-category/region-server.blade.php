@extends('layouts.admin')
@section('nav')
    <div>&nbsp;</div>
@endsection
@section('crumbs', '')

@section('css')
    <style type="text/css">
        input.layui-upload-file {
            display: none;
        }
    </style>
@endsection

@section('content')
    <button class="btn btn-success" id="import-region">导入excel</button>
    <a class="btn btn-default" href="/downloads/FOP直充区服导入模板.xlsx">下载模板</a>

    <table class="table table-bordered table-condensedz m-t" id="my-table">
        <tr>
            <th>分类编码</th>
            <th>分类名</th>
            <th>充值区</th>
            <th>更新时间</th>
            <th>充值服</th>
        </tr>
        @foreach ($dataList as $data)
            <tr>
                <td>{{ $data->category->no }}</td>
                <td>{{ $data->category->name }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->updated_at }}</td>
                <td>
                    @foreach ($data->chargeServers as $chargeServer)
                        {{ $chargeServer->name }}
                        @if (!$loop->last) | @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
    </table>

    {{ $dataList->links() }}
@endsection

@section('js')
    <script src="/layui/layui.js?v=2.4.5"></script>
    <script>
        layui.use(['layer', 'upload'], function () {
            var layer = layui.layer;
            var upload = layui.upload;

            //执行实例
            upload.render({
                elem: '#import-region',
                url: "{{ route('admin.goods.category.import-regions', $category->id) }}",
                exts: "xls|xlsx",
                field: 'excel',
                size: 10000,
                before: function (obj) {
                    layer.load(0, {shade: 0.3});
                },
                done: function (data) {
                    layer.closeAll('loading');

                    if (data.status == 1) {
                        layer.msg('导入成功');
                        window.location.reload();
                    } else {
                        layer.alert(data.message, {icon: 5}, function (index) {
                            window.location.reload();
                        });
                    }
                }
            });
        });
    </script>
@endsection
