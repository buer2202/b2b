// 依赖jquery,layer
function buer_post(url, requestData = {}, callback = true) {
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
            if (typeof(callback) == 'function') {
                callback(data, load);
            } else {
                if (data.status) {
                    if (callback) {
                        layer.close(load);
                        layer.alert('操作成功', function () {
                            window.location.reload();
                        });
                    } else {
                        window.location.reload();
                    }
                } else {
                    layer.close(load);
                    layer.alert(data.message, { icon: 5 });
                }
            }
        }
    });
}
