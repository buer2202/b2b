// 依赖layer插件
function buer_post(url, requestData = {}, callback = null) {
    var load = layer.load(0, { shade: 0.3 });
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: requestData,
        error: function (data) {
            layer.close(load);
            errors = data.responseJSON.errors;
            for (key in errors) {
                layer.alert(errors[key][0], { icon: 5 });
                return false;
            }
        },
        success: function (data) {
            layer.close(load);
            if (callback) {
                callback();
            } else {
                if (data.status) {
                    layer.alert('操作成功', function () {
                        window.location.reload();
                    });
                } else {
                    layer.alert(data.message, { icon: 5 });
                }
            }
        }
    });
}
