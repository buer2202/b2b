<?php
// gateway公共绑定接口
Route::post('web-socket/gateway-worker/bind', 'WebSocket\GatewayWorkerController@bind')->name('home.web-socket.gateway-worker.bind');

// 用户
Route::namespace('User')->prefix('user')->group(function () {
    // 用户首页
    Route::get('/', 'IndexController@index')->name('home.user.index');

    // 用户资料
    Route::get('info', 'InfoController@index')->name('home.user.info');
    Route::post('info/update', 'InfoController@update')->name('home.user.info.update');

    // 结算账号
    Route::get('trading-account', 'TradingAccountController@index')->name('home.user.trading-account');
    Route::post('trading-account', 'TradingAccountController@store')->name('home.user.trading-account.store');

    // 修改密码
    Route::get('reset-password', 'ResetPasswordController@index')->name('home.user.reset-password');
    Route::post('reset-password/update', 'ResetPasswordController@update')->name('home.user.reset-password.update');
});
