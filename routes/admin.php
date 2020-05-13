<?php
// gateway公共绑定接口
Route::post('web-socket/gateway-worker/bind', 'WebSocket\GatewayWorkerController@bind')->name('admin.web-socket.gateway-worker.bind');

Route::get('/', 'IndexController@index')->name('admin.index.index');

// 修改密码
Route::get('password', 'PasswordController@index')->name('admin.password.index');
Route::post('password', 'PasswordController@update')->name('admin.password.update');

// 用户
Route::namespace('User')->prefix('user')->group(function () {
    Route::get('/', 'IndexController@index')->name('admin.user.index.index');
    Route::patch('{id}/status', 'IndexController@status')->name('admin.user.index.status');
});

// 财务管理
Route::namespace('Finance')->prefix('finance')->group(function () {
    // 平台资产
    Route::get('platform-asset', 'PlatformAssetController@index')->name('admin.finance.platform.index');
    // 平台流水
    Route::get('platform-amount-flow', 'PlatformAmountFlowController@index')->name('admin.finance.platform-amount-flow.index');
    Route::post('platform-amount-flow/export', 'PlatformAmountFlowController@export')->name('admin.finance.platform-amount-flow.export');
    // 查看流水订单
    Route::get('platform-amount-flow/order/{id}', 'PlatformAmountFlowController@order')->name('admin.finance.platform-amount-flow.order');

    // 用户资产
    Route::get('user-asset', 'UserAssetController@index')->name('admin.finance.user-asset.index');

    // 用户流水
    Route::get('user-amount-flow', 'UserAmountFlowController@index')->name('admin.finance.user-amount-flow.index');
    Route::get('user-amount-flow/statistics', 'UserAmountFlowController@statistics')->name('admin.finance.user-amount-flow.statistics');

    // 用户提现管理
    Route::get('user-withdraw', 'UserWithdrawController@index')->name('admin.finance.user-withdraw.index');
    Route::post('user-withdraw/{id}/department', 'UserWithdrawController@department')->name('admin.finance.user-withdraw.department');
    Route::post('user-withdraw/{id}/finance', 'UserWithdrawController@finance')->name('admin.finance.user-withdraw.finance');
    Route::post('user-withdraw/{id}/refuse', 'UserWithdrawController@refuse')->name('admin.finance.user-withdraw.refuse');
    Route::post('user-withdraw/{id}/order-id-backfill', 'UserWithdrawController@orderIdBackfill')->name('admin.finance.user-withdraw.order-id-backfill');
    Route::post('user-withdraw/{id}/auto-transfer', 'UserWithdrawController@autoTransfer')->name('admin.finance.user-withdraw.auto-transfer');
    Route::post('user-withdraw/{id}/fulu', 'UserWithdrawController@fulu')->name('admin.finance.user-withdraw.fulu');
    Route::get('user-withdraw/{id}/fulu-info', 'UserWithdrawController@fuluInfo')->name('admin.finance.user-withdraw.fulu-info');

    // 用户加款管理
    Route::get('user-add-money', 'UserAddMoneyController@index')->name('admin.finance.user-add-money.index');
    Route::post('user-add-money', 'UserAddMoneyController@store')->name('admin.finance.user-add-money.store');
    Route::post('user-add-money/agree/{id}', 'UserAddMoneyController@agree')->name('admin.finance.user-add-money.agree');
    Route::post('user-add-money/refuse/{id}', 'UserAddMoneyController@refuse')->name('admin.finance.user-add-money.refuse');
    Route::get('user-add-money/export', 'UserAddMoneyController@export')->name('admin.finance.user-add-money.export');

    // 人工扣款管理
    Route::get('deduct-money', 'DeductMoneyController@index')->name('admin.finance.deduct-money.index');
    Route::post('deduct-money', 'DeductMoneyController@store')->name('admin.finance.deduct-money.store');
    Route::post('deduct-money/agree/{id}', 'DeductMoneyController@agree')->name('admin.finance.deduct-money.agree');
    Route::post('deduct-money/refuse/{id}', 'DeductMoneyController@refuse')->name('admin.finance.deduct-money.refuse');

    // 人工退款管理
    Route::get('refund', 'RefundController@index')->name('admin.finance.refund.index');
    Route::post('refund', 'RefundController@store')->name('admin.finance.refund.store');
    Route::post('refund/agree/{id}', 'RefundController@agree')->name('admin.finance.refund.agree');
    Route::post('refund/refuse/{id}', 'RefundController@refuse')->name('admin.finance.refund.refuse');
});

// 报表查询
Route::namespace('Statement')->prefix('statement')->group(function () {
    // 平台资产日报
    Route::get('platform-asset-daily', 'PlatformAssetDailyController@index')->name('admin.statement.platform-asset-daily.index');
    Route::get('platform-asset-daily/export', 'PlatformAssetDailyController@export')->name('admin.statement.platform-asset-daily.export');

    // 用户资产日报
    Route::get('user-asset-daily', 'UserAssetDailyController@index')->name('admin.statement.user-asset-daily.index');
    Route::get('user-asset-daily/export', 'UserAssetDailyController@export')->name('admin.statement.user-asset-daily.export');
});

// 系统管理
Route::namespace('System')->prefix('system')->group(function () {
    // 获取角色
    Route::get('roles/{id}', 'AdministratorController@roles')->name('admin.system.administrator.roles');
    // 更新角色
    Route::post('roles/{id}', 'AdministratorController@updateRoles')->name('admin.system.administrator.update-roles');

    // 启用
    Route::patch('administrator/restore/{id}', 'AdministratorController@restore')->name('admin.system.administrator.restore');
    Route::resource('administrator', 'AdministratorController', ['names' => [
        'index'   => 'admin.system.administrator.index',
        'store'   => 'admin.system.administrator.store',
        'update'  => 'admin.system.administrator.update',
        'destroy' => 'admin.system.administrator.destroy',
    ], 'only' => ['index', 'store', 'update', 'destroy']]);

    // 角色管理
    Route::post('role/{id}/update-rules', 'RoleController@updateRules')->name('admin.system.role.update-rules');
    Route::resource('role', 'RoleController', ['names' => [
        'index'  => 'admin.system.role.index',
        'store'  => 'admin.system.role.store',
        'update' => 'admin.system.role.update',
        'show'   => 'admin.system.role.show',
    ], 'only' => ['index', 'store', 'show', 'update']]);

    // 权限管理
    Route::resource('rule', 'RuleController', ['names' => [
        'index'  => 'admin.system.rule.index',
        'store'  => 'admin.system.rule.store',
        'show'   => 'admin.system.rule.show',
        'update' => 'admin.system.rule.update',
    ], 'only' => ['index', 'store', 'show', 'update']]);

    // 系统配置管理
    Route::get('config', 'ConfigController@index')->name('admin.system.config.index');
    Route::get('config/show', 'ConfigController@show')->name('admin.system.config.show');
    Route::post('config/update', 'ConfigController@update')->name('admin.system.config.update');
});
