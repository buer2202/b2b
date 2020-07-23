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
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.api-secret', '查看API密钥', '1', 'user', '用户管理', '0', '0', '10250', '{$now}', '{$now}')",
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
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform.index', '平台实时资产', '1', 'finance', '财务管理', '1', '1', '20000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.index', '平台资金流水', '1', 'finance', '财务管理', '1', '1', '20100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.export', '导出', '1', 'finance', '财务管理', '0', '0', '20200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.order', '查看订单', '1', 'finance', '财务管理', '0', '0', '20300', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.1', '分割线', '1', 'finance', '财务管理', '1', '1', '20400', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-asset.index', '用户资产列表', '1', 'finance', '财务管理', '1', '1', '20500', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-amount-flow.index', '用户资金流水', '1', 'finance', '财务管理', '1', '1', '20600', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-amount-flow.statistics', '用户资金统计', '1', 'finance', '财务管理', '1', '1', '20700', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.2', '分割线', '1', 'finance', '财务管理', '1', '1', '20800', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.index', '用户加款管理', '1', 'finance', '财务管理', '1', '1', '20900', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.store', '下单', '1', 'finance', '财务管理', '0', '0', '21000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.agree', '财务审核', '1', 'finance', '财务管理', '0', '0', '21100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.refuse', '财务拒绝', '1', 'finance', '财务管理', '0', '0', '21200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.export', '导出', '1', 'finance', '财务管理', '0', '0', '21300', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.index', '用户提现管理', '1', 'finance', '财务管理', '1', '1', '21500', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.department', '部门审核', '1', 'finance', '财务管理', '0', '0', '21600', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.finance', '财务审核', '1', 'finance', '财务管理', '0', '0', '21700', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.refuse', '财务拒绝', '1', 'finance', '财务管理', '0', '0', '21800', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.offline-pay', '线下提现', '1', 'finance', '财务管理', '0', '0', '21900', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-settlement-account.index', '用户结算账号管理', '1', 'finance', '财务管理', '1', '1', '22000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-settlement-account.destroy', '软删除', '1', 'finance', '财务管理', '0', '0', '22100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.4', '分割线', '1', 'finance', '财务管理', '1', '1', '22200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.index', '人工扣款管理', '1', 'finance', '财务管理', '1', '1', '22300', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.store', '下单', '1', 'finance', '财务管理', '0', '0', '22400', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.agree', '部门审核', '1', 'finance', '财务管理', '0', '0', '22500', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.refuse', '财务审核', '1', 'finance', '财务管理', '0', '0', '22600', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.index', '人工退款管理', '1', 'finance', '财务管理', '1', '1', '22700', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.store', '下单', '1', 'finance', '财务管理', '0', '0', '22800', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.agree', '部门审核', '1', 'finance', '财务管理', '0', '0', '22900', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.refuse', '财务审核', '1', 'finance', '财务管理', '0', '0', '23000', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.platform-asset-daily.index', '平台资产日报', '1', 'statement', '报表查询', '1', '1', '23100', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.platform-asset-daily.export', '导出', '1', 'statement', '报表查询', '0', '0', '23200', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.user-asset-daily.index', '用户资产日报', '1', 'statement', '报表查询', '1', '1', '23300', '{$now}', '{$now}')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.user-asset-daily.export', '导出', '1', 'statement', '报表查询', '0', '0', '23400', '{$now}', '{$now}')",

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
