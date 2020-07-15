<?php
// gateway公共绑定接口
Route::post('web-socket/gateway-worker/bind', 'WebSocket\GatewayWorkerController@bind')->name('home.web-socket.gateway-worker.bind');

// 用户
Route::namespace('User')->prefix('user')->group(function () {
    // 用户首页
    Route::get('/', 'IndexController@index')->name('home.user.index.index');

    // 用户资料
    Route::get('info', 'InfoController@index')->name('home.user.info.index');
    Route::post('info/update', 'InfoController@update')->name('home.user.info.update');

    // 修改密码
    Route::get('reset-password', 'ResetPasswordController@index')->name('home.user.reset-password');
    Route::post('reset-password/update', 'ResetPasswordController@update')->name('home.user.reset-password.update');
});

// 财务管理
Route::namespace('Finance')->prefix('finance')->group(function () {
    // 资产首页
    Route::get('/', 'IndexController@index')->name('home.finance.index.index');

    // 资金流水
    Route::get('amount-flow', 'AmountFlowController@index')->name('home.finance.amount-flow.index');

    // 资产日报
    Route::get('asset-daily', 'AssetDailyController@index')->name('home.finance.asset-daily.index');

    // 加款管理
    Route::get('add-money', 'AddMoneyController@index')->name('home.finance.add-money.index');

    // 提现管理
    Route::get('withdraw', 'WithdrawController@index')->name('home.finance.withdraw.index');
    // 提现申请
    Route::post('withdraw/store', 'WithdrawController@store')->name('home.finance.withdraw.store');
});
