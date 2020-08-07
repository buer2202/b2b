@extends('admin.layouts.base')
@section('breadcrumb')
<li>系统配置管理</li>
@parent
@endsection
@section('title', '系统配置管理')

@section('content')
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">新增</button>

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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">新增</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="data-form">
                    <div class="form-group">
                        <label for="item" class="col-sm-3 control-label">配置项</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="item" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="value" class="col-sm-3 control-label">配置值</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="value" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">配置说明</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" autocomplete="off">
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
    // 添加
    $('#submit-data-form').click(function () {
        buer_post("{{ route('admin.system.config.store') }}", {
            item: $('#item').val(),
            value: $('#value').val(),
            description: $('#description').val()
        }, false);
    });

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
