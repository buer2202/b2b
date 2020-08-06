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
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title">
                <button id="upload-sales-icon" class="btn btn-warning btn-xs">上传</button>
                首页分类图标
            </h3>
        </div>
        <div class="panel-body">
            <img src="{{ $category->icon_sales ? url($category->icon_sales) . '?t=' . time() : '' }}"/>
        </div>
    </div>

    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title">
                <button id="upload-order-icon" class="btn btn-warning btn-xs">上传</button>
                订单列表图标
            </h3>
        </div>
        <div class="panel-body">
            <img src="{{ $category->icon_order ? url($category->icon_order) . '?t=' . time() : '' }}"/>
        </div>
    </div>

    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title">
                <button id="upload-goods-icon" class="btn btn-warning btn-xs">上传</button>
                商品管理图标
            </h3>
        </div>
        <div class="panel-body">
            <img src="{{ $category->icon_goods ? url($category->icon_goods) . '?t=' . time() : '' }}"/>
        </div>
    </div>
@endsection

@section('js')
    <script src="/layui/layui.js?v=2.4.5"></script>
    <script>
        layui.use(['layer', 'upload'], function () {
            var layer = layui.layer;
            var upload = layui.upload;

            function upload_render(domId, iconType) {
                upload.render({
                    elem: '#' + domId,
                    url: "{{ route('admin.goods.category.upload-icon', $category->id) }}",
                    accept: 'images',
                    field: 'icon',
                    data: {icon_type: iconType},
                    size: 10000,
                    before: function (obj) {
                        load = layer.load(4, {shade: 0.2});
                    },
                    done: function (res, index, upload) {
                        layer.close(load);
                        if (res.status === 1) {
                            window.location.reload();
                        } else {
                            layer.alert(res.message);
                        }
                    }
                });
            }

            upload_render('upload-sales-icon', 'icon_sales');
            upload_render('upload-order-icon', 'icon_order');
            upload_render('upload-goods-icon', 'icon_goods');
        });
    </script>
@endsection
