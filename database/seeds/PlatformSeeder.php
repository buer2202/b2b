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
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.status', '设置状态', '1', 'user', '用户管理', '0', '0', '10100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.remark', '设置备注', '1', 'user', '用户管理', '0', '0', '10200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.roles', '编辑角色', '1', 'user', '用户管理', '0', '0', '10300', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.update-roles', '更新角色', '1', 'user', '用户管理', '0', '0', '10400', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.role.index', '用户角色管理', '1', 'user', '用户管理', '1', '1', '11000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.role.store', '添加角色', '1', 'user', '用户管理', '0', '0', '11100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.role.show', '编辑角色', '1', 'user', '用户管理', '0', '0', '11200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.role.update', '更新角色', '1', 'user', '用户管理', '0', '0', '11300', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.role.update-rules', '更新角色权限', '1', 'user', '用户管理', '0', '0', '11400', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.rule.index', '用户权限管理', '1', 'user', '用户管理', '1', '1', '12000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.rule.store', '添加', '1', 'user', '用户管理', '0', '0', '12100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.rule.show', '编辑', '1', 'user', '用户管理', '0', '0', '12200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.rule.update', '更新', '1', 'user', '用户管理', '0', '0', '12300', '{$now}', '{$now}')",
            // 财务管理
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform.index', '平台实时资产', '1', 'finance', '财务管理', '1', '1', '9912600', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.index', '平台资金流水', '1', 'finance', '财务管理', '1', '1', '9912700', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.export', '导出', '1', 'finance', '财务管理', '0', '0', '9912710', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.order', '查看订单', '1', 'finance', '财务管理', '0', '0', '9912720', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.1', '分割线', '1', 'finance', '财务管理', '1', '1', '9912800', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-asset.index', '用户资产列表', '1', 'finance', '财务管理', '1', '1', '9912900', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-amount-flow.index', '用户资金流水', '1', 'finance', '财务管理', '1', '1', '9913000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-amount-flow.statistics', '用户资金统计', '1', 'finance', '财务管理', '1', '1', '9913100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.2', '分割线', '1', 'finance', '财务管理', '1', '1', '9913200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.index', '用户加款管理', '1', 'finance', '财务管理', '1', '1', '9913300', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.store', '下单', '1', 'finance', '财务管理', '0', '0', '9913310', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.agree', '财务审核', '1', 'finance', '财务管理', '0', '0', '9913320', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.refuse', '财务拒绝', '1', 'finance', '财务管理', '0', '0', '9913330', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.export', '导出', '1', 'finance', '财务管理', '0', '0', '9913340', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.3', '分割线', '1', 'finance', '财务管理', '1', '1', '9913400', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.index', '用户提现管理', '1', 'finance', '财务管理', '1', '1', '9913500', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.department', '部门审核', '1', 'finance', '财务管理', '0', '0', '9913510', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.finance', '财务审核', '1', 'finance', '财务管理', '0', '0', '9913520', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.refuse', '财务拒绝', '1', 'finance', '财务管理', '0', '0', '9913530', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.order-id-backfill', '单号回填', '1', 'finance', '财务管理', '0', '0', '9913540', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.auto-transfer', '自动转账', '1', 'finance', '财务管理', '0', '0', '9913550', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.fulu', '提现', '0', 'finance', '财务管理', '0', '0', '9913560', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.fulu-info', '办款详情', '0', 'finance', '财务管理', '0', '0', '9913570', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.4', '分割线', '1', 'finance', '财务管理', '1', '1', '9913700', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.index', '人工扣款管理', '1', 'finance', '财务管理', '1', '1', '9913800', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.store', '下单', '1', 'finance', '财务管理', '0', '0', '9913810', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.agree', '部门审核', '1', 'finance', '财务管理', '0', '0', '9913820', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.refuse', '财务审核', '1', 'finance', '财务管理', '0', '0', '9913830', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.index', '人工退款管理', '1', 'finance', '财务管理', '1', '1', '9913900', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.store', '下单', '1', 'finance', '财务管理', '0', '0', '9913910', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.agree', '部门审核', '1', 'finance', '财务管理', '0', '0', '9913920', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.refuse', '财务审核', '1', 'finance', '财务管理', '0', '0', '9913930', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.platform-asset-daily.index', '平台资产日报', '1', 'statement', '报表查询', '1', '1', '9914000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.platform-asset-daily.export', '导出', '1', 'statement', '报表查询', '0', '0', '9914010', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.user-asset-daily.index', '用户资产日报', '1', 'statement', '报表查询', '1', '1', '9914100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.user-asset-daily.export', '导出', '1', 'statement', '报表查询', '0', '0', '9914110', '{$now}', '{$now}')",

            // home
            // 用户管理
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.user.index.index', '用户中心', '1', 'user', '用户管理', '1', '1', '10000', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.user.info.index', '用户资料', '1', 'user', '用户管理', '1', '1', '10100', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.user.info.update', '修改用户资料', '1', 'user', '用户管理', '0', '0', '10200', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.user.reset-password', '修改密码', '1', 'user', '用户管理', '1', '1', '10300', '{$now}', '{$now}')",
            "INSERT INTO `home_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('home.user.reset-password.update', '修改密码提交', '1', 'user', '用户管理', '0', '0', '10400', '{$now}', '{$now}')",
        ];

        foreach ($sql as $s) {
            DB::insert($s);
        }
    }
}
