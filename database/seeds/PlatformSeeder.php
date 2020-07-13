<?php

use App\Models\Admin;
use App\Models\AdminAuthRole;
use App\Models\AdminAuthUserRole;
use Buer\Asset\Models\PlatformAsset;
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

        // 插入用户角色
        AdminAuthUserRole::create(['admin_id' => 1, 'admin_auth_role_id' => 1]);

        // 插入权限
        $sql = [
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.index', '用户资料管理', '1', 'user', '用户管理', '1', '1', '10000', '2020-05-13 11:46', '2020-05-13 11:46')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.status', '设置状态', '1', 'user', '用户管理', '0', '0', '10100', '2020-05-13 11:46', '2020-05-13 11:46')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.user.index.remark', '设置备注', '1', 'user', '用户管理', '0', '0', '10200', '2020-05-13 11:46', '2020-05-13 11:46')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform.index', '平台实时资产', '1', 'finance', '财务管理', '1', '1', '9912600', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.index', '平台资金流水', '1', 'finance', '财务管理', '1', '1', '9912700', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.export', '导出', '1', 'finance', '财务管理', '0', '0', '9912710', '2018-10-19 00:00:00', '2019-07-31 15:12:49')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.platform-amount-flow.order', '查看订单', '1', 'finance', '财务管理', '0', '0', '9912720', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.1', '分割线', '1', 'finance', '财务管理', '1', '1', '9912800', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-asset.index', '用户资产列表', '1', 'finance', '财务管理', '1', '1', '9912900', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-amount-flow.index', '用户资金流水', '1', 'finance', '财务管理', '1', '1', '9913000', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-amount-flow.statistics', '用户资金统计', '1', 'finance', '财务管理', '1', '1', '9913100', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.2', '分割线', '1', 'finance', '财务管理', '1', '1', '9913200', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.index', '用户加款管理', '1', 'finance', '财务管理', '1', '1', '9913300', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.store', '下单', '1', 'finance', '财务管理', '0', '0', '9913310', '2018-10-19 00:00:00', '2018-12-19 14:53:55')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.agree', '财务审核', '1', 'finance', '财务管理', '0', '0', '9913320', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.refuse', '财务拒绝', '1', 'finance', '财务管理', '0', '0', '9913330', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-add-money.export', '导出', '1', 'finance', '财务管理', '0', '0', '9913340', '2018-12-27 18:04:47', '2019-07-31 15:12:56')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.3', '分割线', '1', 'finance', '财务管理', '1', '1', '9913400', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.index', '用户提现管理', '1', 'finance', '财务管理', '1', '1', '9913500', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.department', '部门审核', '1', 'finance', '财务管理', '0', '0', '9913510', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.finance', '财务审核', '1', 'finance', '财务管理', '0', '0', '9913520', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.refuse', '财务拒绝', '1', 'finance', '财务管理', '0', '0', '9913530', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.order-id-backfill', '单号回填', '1', 'finance', '财务管理', '0', '0', '9913540', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.auto-transfer', '自动转账', '1', 'finance', '财务管理', '0', '0', '9913550', '2018-11-14 15:12:41', '2018-11-14 15:12:41')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.fulu', '提现', '0', 'finance', '财务管理', '0', '0', '9913560', '2019-03-06 17:24:56', '2019-03-06 17:24:56')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.user-withdraw.fulu-info', '办款详情', '0', 'finance', '财务管理', '0', '0', '9913570', '2019-03-13 10:44:32', '2019-03-13 14:22:21')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.separator-divider.4', '分割线', '1', 'finance', '财务管理', '1', '1', '9913700', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.index', '人工扣款管理', '1', 'finance', '财务管理', '1', '1', '9913800', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.store', '下单', '1', 'finance', '财务管理', '0', '0', '9913810', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.agree', '部门审核', '1', 'finance', '财务管理', '0', '0', '9913820', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.deduct-money.refuse', '财务审核', '1', 'finance', '财务管理', '0', '0', '9913830', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.index', '人工退款管理', '1', 'finance', '财务管理', '1', '1', '9913900', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.store', '下单', '1', 'finance', '财务管理', '0', '0', '9913910', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.agree', '部门审核', '1', 'finance', '财务管理', '0', '0', '9913920', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.finance.refund.refuse', '财务审核', '1', 'finance', '财务管理', '0', '0', '9913930', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.platform-asset-daily.index', '平台资产日报', '1', 'statement', '报表查询', '1', '1', '9914000', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.platform-asset-daily.export', '导出', '1', 'statement', '报表查询', '0', '0', '9914010', '2018-10-19 00:00:00', '2019-08-21 13:41:44')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.user-asset-daily.index', '用户资产日报', '1', 'statement', '报表查询', '1', '1', '9914100', '2018-10-19 00:00:00', '2018-10-19 00:00:00')",
            "INSERT INTO `admin_auth_rules` (`name`, `title`, `status`, `group`, `group_name`, `menu_show`, `menu_click`, `sortord`, `created_at`, `updated_at`) VALUES ('admin.statement.user-asset-daily.export', '导出', '1', 'statement', '报表查询', '0', '0', '9914110', '2018-10-19 00:00:00', '2019-08-21 13:41:50')",
        ];

        foreach ($sql as $s) {
            DB::insert($s);
        }
    }
}
