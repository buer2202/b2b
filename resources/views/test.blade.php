<!DOCTYPE html>
<html>

<head>
    <title>Laravel5.5-Admin Test</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div id="ws-msg"></div>

    <script src="/jquery-weui/lib/jquery-2.1.4.js"></script>
    <script>
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        // 异步通知
        ws = new WebSocket("{{ config('app.ws_url') }}");
        ws.onmessage = function (e) {
            // json数据转换成js对象
            var data = eval("(" + e.data + ")");
            var type = data.type || '';

            switch (type) {
                case 'init':
                    $('#ws-msg').append('<p>WebSocret 初始化成功！</p>');

                    $.post("{{ route('gateway-worker.bind') }}", {
                        client_id: data.client_id,
                        identify: "xxxxxxxxx"
                    }, function (data) {
                        $('#ws-msg').append('<p>WebSocret 绑定成功！</p>');
                    }, 'json');
                    break;
                case '@heart@': // 心跳
                    $('#ws-msg').append('<p>WebSocret 心跳连接！</p>');
                    break;
                default :
                    break;
            }
        }
    </script>
</body>

</html>
