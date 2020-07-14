<?php
return [
    // 不参加权限验证的路由
    'admin_except' => [
        'admin.index.index',
        'admin.password.index',
        'admin.password.update',
        'admin.web-socket.gateway-worker.bind',
        'admin.index.download',
    ],

    // 不参加权限验证的路由
    'home_except' => [
        'home.user.index.index',
        'home.web-socket.gateway-worker.bind',
    ],
];
