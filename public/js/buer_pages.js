// 滚动翻页插件，依赖jQuery WeUI扩展组件
(function ($) {
    $.fn.extend({
        "buerPages": function (options) {
            var that = this;
            var opts = $.extend({
                pageUrl: '/'
                , domHtml: function () {return '';}
            }, options); //使用jQuery.extend 覆盖插件默认参数

            show_page();

            var loading = false;  //状态标记
            $(document.body).infinite().on("infinite", function() {
                if (loading) return false;
                loading = true;
                show_page(opts.pageUrl);
            });

            function show_page() {
                if (!opts.pageUrl) {
                    return null;
                }

                $.get(opts.pageUrl, function (data) {
                    var html;
                    for (var i = 0; i < data.data.length; i++) {
                        html = opts.domHtml(data.data[i]);
                        that.append(html);
                    }

                    loading = false;
                    opts.pageUrl = data.next_page_url;

                    if (!opts.pageUrl) {
                        $(document.body).destroyInfinite();
                        $('.weui-loadmore').text('已经到底了');
                    }
                });
            }

            return this;
        }
    });
})(jQuery);
