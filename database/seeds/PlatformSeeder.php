<?php

use App\Models\Admin;
use App\Models\AdminAuthRole;
use App\Models\AdminAuthUserRole;
use App\Models\HomeAuthRole;
use Buer\Asset\Models\PlatformAsset;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 插入平台资金
        PlatformAsset::create(['id' => 1]);

        // 插入超级管理员账号
        Admin::create([
            'id'             => 1,
            'name'           => 'administrator',
            'password'       => bcrypt('admin'),
            'remember_token' => str_random(10),
        ]);

        // 插入超级管理员角色
        AdminAuthRole::create(['id' => 1, 'name' => '超级管理员', 'status' => 1]);

        // 插入管理员用户角色
        AdminAuthUserRole::create(['admin_id' => 1, 'admin_auth_role_id' => 1]);

        // 插入前台用户角色
        HomeAuthRole::create(['name' => '开发者', 'status' => 1]);

        // 插入权限
        $now = Carbon::now()->toDateTimeString();
        $sql = [
            // admin
            // 用户管理
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.index', '用户资料管理', '1', 'user', '用户管理', '1', '1', '10000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.status', '设置状态', '1', 'user', '用户管理', '0', '0', '10010', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.remark', '设置备注', '1', 'user', '用户管理', '0', '0', '10020', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.api-secret', '查看API密钥', '1', 'user', '用户管理', '0', '0', '10030', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.roles', '编辑角色', '1', 'user', '用户管理', '0', '0', '10030', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.update-roles', '更新角色', '1', 'user', '用户管理', '0', '0', '10040', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.role.index', '用户角色管理', '1', 'user', '用户管理', '1', '1', '10100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.role.store', '添加角色', '1', 'user', '用户管理', '0', '0', '10120', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.role.show', '编辑角色', '1', 'user', '用户管理', '0', '0', '10130', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.role.update', '更新角色', '1', 'user', '用户管理', '0', '0', '10140', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.role.update-rules', '更新角色权限', '1', 'user', '用户管理', '0', '0', '10150', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.rule.index', '用户权限管理', '1', 'user', '用户管理', '1', '1', '10200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.rule.store', '添加', '1', 'user', '用户管理', '0', '0', '10210', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.rule.show', '编辑', '1', 'user', '用户管理', '0', '0', '10220', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.rule.update', '更新', '1', 'user', '用户管理', '0', '0', '10230', '{$now}', '{$now}')",
            // 财务管理
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform.index', '平台实时资产', '1', 'finance', '财务管理', '1', '1', '11000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.index', '平台资金流水', '1', 'finance', '财务管理', '1', '1', '11100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.export', '导出', '1', 'finance', '财务管理', '0', '0', '11110', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.order', '查看订单', '1', 'finance', '财务管理', '0', '0', '11120', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.1', '分割线', '1', 'finance', '财务管理', '1', '1', '11200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-asset.index', '用户资产列表', '1', 'finance', '财务管理', '1', '1', '11300', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-amount-flow.index', '用户资金流水', '1', 'finance', '财务管理', '1', '1', '11400', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-amount-flow.statistics', '用户资金统计', '1', 'finance', '财务管理', '1', '1', '11500', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.2', '分割线', '1', 'finance', '财务管理', '1', '1', '11600', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.index', '用户加款管理', '1', 'finance', '财务管理', '1', '1', '11700', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.store', '下单', '1', 'finance', '财务管理', '0', '0', '11710', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.agree', '财务审核', '1', 'finance', '财务管理', '0', '0', '11720', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.refuse', '财务拒绝', '1', 'finance', '财务管理', '0', '0', '11730', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.export', '导出', '1', 'finance', '财务管理', '0', '0', '11740', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.index', '用户提现管理', '1', 'finance', '财务管理', '1', '1', '11800', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.department', '部门审核', '1', 'finance', '财务管理', '0', '0', '11810', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.finance', '财务审核', '1', 'finance', '财务管理', '0', '0', '11820', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.refuse', '财务拒绝', '1', 'finance', '财务管理', '0', '0', '11830', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.offline-pay', '线下提现', '1', 'finance', '财务管理', '0', '0', '11840', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-settlement-account.index', '用户结算账号管理', '1', 'finance', '财务管理', '1', '1', '11900', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-settlement-account.destroy', '软删除', '1', 'finance', '财务管理', '0', '0', '11910', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.4', '分割线', '1', 'finance', '财务管理', '1', '1', '12000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.index', '人工扣款管理', '1', 'finance', '财务管理', '1', '1', '12100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.store', '下单', '1', 'finance', '财务管理', '0', '0', '12110', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.agree', '部门审核', '1', 'finance', '财务管理', '0', '0', '12120', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.refuse', '财务审核', '1', 'finance', '财务管理', '0', '0', '12130', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.index', '人工退款管理', '1', 'finance', '财务管理', '1', '1', '12200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.store', '下单', '1', 'finance', '财务管理', '0', '0', '12210', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.agree', '部门审核', '1', 'finance', '财务管理', '0', '0', '12220', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.refuse', '财务审核', '1', 'finance', '财务管理', '0', '0', '12230', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.platform-asset-daily.index', '平台资产日报', '1', 'statement', '报表查询', '1', '1', '12300', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.platform-asset-daily.export', '导出', '1', 'statement', '报表查询', '0', '0', '12310', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.user-asset-daily.index', '用户资产日报', '1', 'statement', '报表查询', '1', '1', '12400', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.user-asset-daily.export', '导出', '1', 'statement', '报表查询', '0', '0', '12410', '{$now}', '{$now}')",
            // 商品价格
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group.index', '价格组管理', '1', 'goods-price', '商品价格', '1', '1', '13000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group.store', '添加', '1', 'goods-price', '商品价格', '0', '0', '13010', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group.show', '编辑', '1', 'goods-price', '商品价格', '0', '0', '13020', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group.update', '更新', '1', 'goods-price', '商品价格', '0', '0', '13030', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group.search-user', '查找用户', '1', 'goods-price', '商品价格', '0', '0', '13040', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-user.inside', '组内用户', '1', 'goods-price', '商品价格', '0', '0', '13050', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-user.outside', '组外用户', '1', 'goods-price', '商品价格', '0', '0', '13060', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-user.add', '添加组用户', '1', 'goods-price', '商品价格', '0', '0', '13070', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-user.delete', '删除组用户', '1', 'goods-price', '商品价格', '0', '0', '13080', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-user.move', '移动组用户', '1', 'goods-price', '商品价格', '0', '0', '13090', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-goods.inside', '组内商品', '1', 'goods-price', '商品价格', '0', '0', '13100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-goods.outside', '组外商品', '1', 'goods-price', '商品价格', '0', '0', '13110', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-goods.add', '添加组商品', '1', 'goods-price', '商品价格', '0', '0', '13120', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-goods.edit-price', '编辑商品价格', '1', 'goods-price', '商品价格', '0', '0', '13130', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-goods.delete', '删除组商品', '1', 'goods-price', '商品价格', '0', '0', '13140', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.goods-price.price-group-goods.update-price', '更新商品价格', '1', 'goods-price', '商品价格', '0', '0', '13150', '{$now}', '{$now}')",

            // home
            // 用户管理
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.user.index.index', '用户中心', '1', 'user', '用户管理', '1', '1', '10000', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.user.info.index', '用户资料', '1', 'user', '用户管理', '1', '1', '10100', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.user.info.update', '修改用户资料', '1', 'user', '用户管理', '0', '0', '10200', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.user.reset-password', '修改密码', '1', 'user', '用户管理', '1', '1', '10300', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.user.reset-password.update', '提交', '1', 'user', '用户管理', '0', '0', '10400', '{$now}', '{$now}')",

            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.finance.index.index', '我的资金', '1', 'finance', '财务管理', '1', '1', '20000', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.finance.amount-flow.index', '资金流水', '1', 'finance', '财务管理', '1', '1', '20100', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.finance.asset-daily.index', '资金日报', '1', 'finance', '财务管理', '1', '1', '20200', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.finance.add-money.index', '加款管理', '1', 'finance', '财务管理', '1', '1', '20300', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.finance.withdraw.index', '提现管理', '1', 'finance', '财务管理', '1', '1', '20400', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.finance.withdraw.store', '提现申请', '1', 'finance', '财务管理', '0', '0', '20500', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.finance.settlement-account.index', '结算账号', '1', 'finance', '财务管理', '1', '1', '20600', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.finance.settlement-account.store', '添加', '1', 'finance', '财务管理', '0', '0', '20700', '{$now}', '{$now}')",
        ];

        foreach ($sql as $s) {
            DB::insert($s);
        }
    }
}
