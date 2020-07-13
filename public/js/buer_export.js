// 异步导出插件
(function ($) {
    $.fn.extend({
        asyncExport: function (options) {
            var that = this;
            var opts = $.extend({
                postUrl: '',
                postData: {},
                callback: function (data) {
                    if (data.status) {
                        that.attr('disabled', true).text('已进入任务队列，请稍后...');
                    } else {
                        layer.alert(data.message, {icon: 5});
                    }
                },
                wsServerUrl: '',
                wsInitUrl: '',
                taskName: ''
            }, options); //使用jQuery.extend 覆盖插件默认参数

            this.click(function () {
                $.post(opts.postUrl, opts.postData, opts.callback, 'json');
            });

            // 异步下载
            var ws = new WebSocket(opts.wsServerUrl);
            ws.onmessage = function (e) {
                // json数据转换成js对象
                var data = eval("(" + e.data + ")");
                var type = data.type || '';

                switch (type) {
                    case 'init':
                        $.post(opts.wsInitUrl, {client_id: data.client_id}, function (data) {}, 'json');
                        break;

                    case 'exporting_' + opts.taskName:
                        that.attr('disabled', true).text('已导出 ' + data.message + ' 条');
                        break;

                    case 'complete_' + opts.taskName:
                        that.attr('disabled', true).text('导出完成');
                        window.location.href = data.message;
                        break;

                    default :
                        break;
                }
            };

            return this;
        }
    });
})(window.jQuery);
