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
    // 用户详情
    Route::get('api-secret/{id}', 'IndexController@apiSecret')->name('admin.user.index.api-secret');
    // 获取角色
    Route::get('roles/{id}', 'IndexController@roles')->name('admin.user.index.roles');
    // 更新角色
    Route::post('roles/{id}', 'IndexController@updateRoles')->name('admin.user.index.update-roles');
    // 设置备注
    Route::post('remark/{id}', 'IndexController@remark')->name('admin.user.index.remark');

    // 角色管理
    Route::post('role/update-rules/{id}', 'RoleController@updateRules')->name('admin.user.role.update-rules');
    Route::resource('role', 'RoleController', ['names' => [
        'index'  => 'admin.user.role.index',
        'store'  => 'admin.user.role.store',
        'update' => 'admin.user.role.update',
        'show'   => 'admin.user.role.show',
    ], 'only' => ['index', 'store', 'show', 'update']]);

    // 权限管理
    Route::resource('rule', 'RuleController', ['names' => [
        'index'  => 'admin.user.rule.index',
        'store'  => 'admin.user.rule.store',
        'show'   => 'admin.user.rule.show',
        'update' => 'admin.user.rule.update',
    ], 'only' => ['index', 'store', 'show', 'update']]);
});

// 商品管理
Route::namespace('GoodsPirce')->prefix('goods-price')->group(function () {
    // 价格组管理
    Route::get('price-group/search-user', 'PriceGroupController@searchUser')->name('admin.goods-price.price-group.search-user');
    Route::resource('price-group', 'PriceGroupController', ['names' => [
        'index'  => 'admin.goods-price.price-group.index',
        'store'  => 'admin.goods-price.price-group.store',
        'update' => 'admin.goods-price.price-group.update',
    ], 'only' => ['index', 'store', 'update']]);

    // 组用户管理
    Route::prefix('{groupId}/price-group-user')->group(function () {
        Route::get('inside', 'PriceGroupUserController@inside')->name('admin.goods-price.price-group-user.inside');
        Route::get('outside', 'PriceGroupUserController@outside')->name('admin.goods-price.price-group-user.outside');
        Route::post('add', 'PriceGroupUserController@add')->name('admin.goods-price.price-group-user.add');
        Route::post('delete', 'PriceGroupUserController@delete')->name('admin.goods-price.price-group-user.delete');
        Route::post('move', 'PriceGroupUserController@move')->name('admin.goods-price.price-group-user.move');
    });

    // 组商品管理
    Route::prefix('{groupId}/price-group-goods')->group(function () {
        Route::get('inside', 'PriceGroupGoodsController@inside')->name('admin.goods-price.price-group-goods.inside');
        Route::get('outside', 'PriceGroupGoodsController@outside')->name('admin.goods-price.price-group-goods.outside');
        Route::post('add', 'PriceGroupGoodsController@add')->name('admin.goods-price.price-group-goods.add');
        Route::post('delete', 'PriceGroupUserController@delete')->name('admin.goods-price.price-group-goods.delete');
    });
    Route::get('price-group-goods/{id}', 'PriceGroupGoodsController@editPrice')->name('admin.goods-price.price-group-goods.edit-price');
    Route::post('price-group-goods/{id}', 'PriceGroupGoodsController@updatePrice')->name('admin.goods-price.price-group-goods.update-price');

    // 基础商品分类
    Route::resource('goods-category', 'GoodsCategoryController', ['names' => [
        'index'  => 'admin.goods-price.goods-category.index',
        'store'  => 'admin.goods-price.goods-category.store',
        'show'   => 'admin.goods-price.goods-category.show',
        'update' => 'admin.goods-price.goods-category.update',
    ], 'only' => ['index', 'store', 'show', 'update']]);

    // 基础商品资料
    Route::resource('goods', 'GoodsController', ['names' => [
        'index'  => 'admin.goods-price.goods.index',
        'store'  => 'admin.goods-price.goods.store',
        'show'   => 'admin.goods-price.goods.show',
        'update' => 'admin.goods-price.goods.update',
    ], 'only' => ['index', 'store', 'show', 'update']]);
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

    // 用户加款管理
    Route::get('user-add-money', 'UserAddMoneyController@index')->name('admin.finance.user-add-money.index');
    Route::post('user-add-money', 'UserAddMoneyController@store')->name('admin.finance.user-add-money.store');
    Route::post('user-add-money/agree/{id}', 'UserAddMoneyController@agree')->name('admin.finance.user-add-money.agree');
    Route::post('user-add-money/refuse/{id}', 'UserAddMoneyController@refuse')->name('admin.finance.user-add-money.refuse');
    Route::get('user-add-money/export', 'UserAddMoneyController@export')->name('admin.finance.user-add-money.export');

    // 用户提现管理
    Route::get('user-withdraw', 'UserWithdrawController@index')->name('admin.finance.user-withdraw.index');
    Route::post('user-withdraw/{id}/department', 'UserWithdrawController@department')->name('admin.finance.user-withdraw.department');
    Route::post('user-withdraw/{id}/finance', 'UserWithdrawController@finance')->name('admin.finance.user-withdraw.finance');
    Route::post('user-withdraw/{id}/refuse', 'UserWithdrawController@refuse')->name('admin.finance.user-withdraw.refuse');
    Route::post('user-withdraw/{id}/offline-pay', 'UserWithdrawController@offlinePay')->name('admin.finance.user-withdraw.offline-pay');

    // 结算账号管理
    Route::get('user-settlement-account', 'UserSettlementAccountController@index')->name('admin.finance.user-settlement-account.index');
    Route::delete('user-settlement-account/{id}', 'UserSettlementAccountController@destroy')->name('admin.finance.user-settlement-account.destroy');

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
    Route::post('config/store', 'ConfigController@store')->name('admin.system.config.store');
    Route::get('config/show', 'ConfigController@show')->name('admin.system.config.show');
    Route::post('config/update', 'ConfigController@update')->name('admin.system.config.update');
});
