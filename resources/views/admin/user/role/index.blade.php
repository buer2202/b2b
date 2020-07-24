@extends('admin.layouts.base')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5" id="role" style="min-width: 500px">
            <div class="list-group">
                <p class="list-group-item list-group-item-success">角色列表</p>

                @foreach ($roleList as $role)
                <p class="list-group-item form-inline {{ $role->status == 0 ? 'disabled' : '' }}">
                    <span class="role_name">{{ $role->name }}</span>
                    <input class="form-control input-sm" style="display:none" name="name" />
                    <span class="button-span pull-right" data-update="{{ route('admin.user.role.update', $role->id) }}">
                        <button type="button" class="btn btn-warning btn-xs edit">编辑</button>
                        <button type="button" class="btn btn-danger btn-xs off" data-status="{{ $role->status }}"
                            style="display:none" data-tag="0">禁用</button>
                        <button type="button" class="btn btn-success btn-xs on" data-status="{{ $role->status }}"
                            style="display:none" data-tag="1">启用</button>
                        <button type="button" class="btn btn-success btn-sm confirm" style="display:none">确认</button>
                        <button type="button" class="btn btn-default btn-sm cancel" style="display:none">取消</button>
                        <button type="button" class="btn btn-info btn-xs accredit"
                            data-rules="{{ route('admin.user.role.show', $role->id) }}"
                            data-update_rule="{{ route('admin.user.role.update-rules', $role->id) }}">授权</button>
                    </span>
                </p>
                @endforeach
                <form id="add-role-form" class="form-inline list-group-item new-role" style="display:none"
                    action="{{ route('admin.user.role.store') }}" method="post">
                    <input class="form-control input-sm" name="name" placeholder="角色名称" />
                    <span class="button-span">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success btn-sm">添加</button>
                    </span>
                </form>
                <p class="list-group-item">
                    <button type="button" class="btn btn-primary btn-sm" id="add-new-button">添加新角色</button>
                </p>
            </div>
        </div>

        <div class="col-md-7" id="rule" style="display:none">
            <form id="rules-form" action="" method="post">
                <div class="list-group">
                    <div class="list-group-item list-group-item-success">
                        权限列表
                        <span id="now_role"></span>
                    </div>

                    @foreach ($ruleGroup as $rule)
                    <div class="list-group-item">
                        <label class="checkbox-inline">
                            <input type="checkbox" class="select-all" />
                            <b>{{ $rule['group_name'] }}</b>
                        </label>

                        <p style="margin-bottom:0px">
                            @foreach ($rule['detail'] as $detail)

                            @if ($detail['menu_show'] && $detail['menu_click'])
                        </p>
                        <p style="margin:0 0 0 20px">
                            @endif

                            <label class="checkbox-inline">
                                <input type="checkbox" class="rule-item" id="{{ $detail['id'] }}" name="rule_ids[]"
                                    value="{{ $detail['id'] }}" />
                                @if (!empty($detail['menu_show']))
                                <b>{{ $detail['title'] }}</b>
                                @else
                                {{ $detail['title'] }}
                                @endif
                            </label>
                            @endforeach
                        </p>
                    </div>
                    @endforeach

                    <div class="list-group-item">
                        {{ csrf_field() }}
                        <button type="submit" id="submit-rule" class="btn btn-primary btn-sm disabled">授权</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $("#role .button-span .off[data-status=1]").show(); //启用时，显示禁用按钮
    $("#role .button-span .on[data-status=0]").show(); //禁用时，显示启用按钮

    // 编辑角色名
    $("#role .edit").click(function () {
        //按钮切换
        $(this).siblings(".confirm, .cancel").show().siblings(".edit, .off, .on, .accredit").hide();
        $(this).parent().siblings("input").show().siblings(".role_name").hide();

        // 赋值input
        $(this).parent().siblings("[name='name']").val($(this).parent().siblings(".role_name").text());
    });

    // 取消编辑角色名
    $("#role .cancel").click(function () {
        $(this).siblings(".edit, .accredit").show().siblings(".confirm, .cancel").hide(); //按钮切换
        $(this).siblings(".off[data-status=1]").show(); //启用时，显示禁用按钮
        $(this).siblings(".on[data-status=0]").show(); //禁用时，显示启用按钮
        $(this).parent().siblings("input").hide().siblings(".role_name").show();
    });

    // 提交角色名
    $("#role .confirm").click(function () {
        var $that = $(this);
        buer_post($that.parent().data('update'), {
            name: $that.parent().siblings("[name='name']").val(),
            _method: 'put'
        }, function (data, load) {
            layer.close(load);
            if (data.status === 1) {
                // 更新文字
                $that.parent().siblings(".role_name").text(data.contents.name);

                //按钮切换
                $that.parent().find(".confirm, .cancel").hide().siblings(".edit, .accredit").show();
                $that.siblings(".off[data-status=1]").show(); // 启用时，显示禁用按钮
                $that.siblings(".on[data-status=0]").show(); // 禁用时，显示启用按钮
                $that.parent().siblings("input").hide().siblings(".role_name").show();
            } else {
                layer.alert(data.message, {
                    icon: 5
                });
            }
        });
    });

    // 禁用、启用
    $("#role .off, #role .on").click(function () {
        var $that = $(this);
        buer_post($that.parent().data('update'), {
            status: $that.data("tag"),
            _method: 'patch'
        }, function (data, load) {
            layer.close(load);
            if (data.status === 1) {
                $that.parent().find(".off, .on").data("status", $that.data("tag"));

                if ($that.data("tag") == "1") {
                    $that.siblings(".off").show().siblings(".on").hide();
                    $that.parents(".list-group-item").removeClass('disabled');
                } else {
                    $that.siblings(".on").show().siblings(".off").hide();
                    $that.parents(".list-group-item").addClass('disabled');
                }
            } else {
                layer.msg(data.message, {
                    icon: 7
                });
            }
        });
    });

    // 选择角色事件
    $("#role .accredit").click(function () {
        var load = layer.load(0, {
            shade: 0.2
        });
        $("#rule :checkbox").prop("checked", false); // 全不选

        $that = $(this);
        $('#rules-form').attr('action', $that.data('update_rule'));
        var role_name = $(this).parent().siblings(".role_name").text();

        $.get($that.data('rules'), function (data) {
            layer.close(load);
            if (data.status === 1) {
                // 界面
                $("#rule").show();
                $that.parents("p").addClass('active').siblings().removeClass('active');
                $("#now_role").text("- 当前操作：授权->" + role_name); // 显示编辑对象
                $("#submit-rule").text("授权给->" + role_name).removeClass('disabled'); //改变按钮状态

                // 数据
                var ruleList = data.contents;
                if (ruleList == undefined) return false;
                for (var i = 0; i < ruleList.length; i++) {
                    $("#rule #" + ruleList[i].id).prop("checked", true);
                };

                // 检测所有全选按钮
                $(".select-all").each(function () {
                    var $this = $(this);
                    $this.prop('checked', $this.closest('.list-group-item').find('.rule-item')
                        .length == $this.closest('.list-group-item').find(
                            '.rule-item:checked').length);
                });
            } else {
                layer.msg(data.message, {
                    icon: 5
                }, function () {
                    window.location.reload();
                });
            }
        });
    });

    // 全选按钮
    $(".select-all").click(function () {
        if ($(this).prop("checked")) {
            $(this).parent().siblings().find(":checkbox").prop("checked", true);
        } else {
            $(this).parent().siblings().find(":checkbox").prop("checked", false);
        }
    });

    // 取消一个选择
    $(".rule-item").click(function () {
        var $this = $(this);
        if (!$this.prop("checked")) {
            $this.parents(".list-group-item").find("input.select-all").prop("checked", false);
        } else {
            $this.parents(".list-group-item").find("input.select-all").prop("checked", $this.closest(
                '.list-group-item').find('.rule-item').length == $this.closest('.list-group-item').find(
                '.rule-item:checked').length);
        }
    });

    // 添加角色框显示
    $("#role #add-new-button").click(function () {
        $("#role .new-role").show();
        $(this).addClass('disabled');
    });

    // 添加角色事件
    $("#add-role-form").submit(function () {
        if (!$("#role .new-role [name='name']").val()) {
            layer.msg('请填写角色名称！', {
                icon: 7
            });
            return false;
        }
    });

    // 提交角色权限
    $('#rules-form').submit(function () {
        buer_post($(this).attr('action'), $(this).serialize(), function (data, load) {
            layer.close(load);

            if (data.status == 1) {
                layer.msg('操作成功');
            } else {
                layer.msg(data.message);
            }
        });

        return false;
    });
</script>
@endsection
