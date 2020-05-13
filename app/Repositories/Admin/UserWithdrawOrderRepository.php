<?php
namespace App\Repositories\Admin;

use App\Models\UserWithdrawOrder;
use App\Models\FuluWithdrawNotify;
use App\Exceptions\CustomException;
use DB;
use Asset;
use Auth;
use App\Services\AssetApi;
use App\Services\Fulu\FuluFinance;

class UserWithdrawOrderRepository
{
    public static function getList($no, $userId, $status)
    {
        $dataList = UserWithdrawOrder::orderBy('id', 'desc')
            ->when($no, function ($query) use ($no) {
                return $query->where('no', $no);
            })
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->paginate(20);

        return $dataList;
    }

    public static function find($id)
    {
        $model = UserWithdrawOrder::lockForUpdate()->find($id);
        if (empty($model)) {
            throw new CustomException('数据不存在');
        }

        return $model;
    }

    // 部门审核
    public static function department($id)
    {
        DB::beginTransaction();

        $model = self::find($id);
        if ($model->status != 1) {
            throw new CustomException('状态不正确');
        }

        $model->status = 2;
        $model->department_auditor = Auth::id();
        $model->department_audit_at = date('Y-m-d H:i:s');
        if (!$model->save()) {
            DB::rollback();
            throw new CustomException('操作失败');
        }

        DB::commit();
        return true;
    }

    // 财务审核
    public static function finance($id)
    {
        DB::beginTransaction();

        $model = self::find($id);
        if ($model->status != 2) {
            throw new CustomException('状态不正确');
        }

        $model->status = 3;
        $model->finance_auditor = Auth::id();
        $model->finance_audit_at = date('Y-m-d H:i:s');
        if (!$model->save()) {
            DB::rollback();
            throw new CustomException('操作失败');
        }

        DB::commit();
        return true;
    }

    // 拒绝提现
    public static function refuse($id)
    {
        DB::beginTransaction();

        $model = self::find($id);
        if (!in_array($model->status, [2, 3])) {
            throw new CustomException('状态不正确');
        }

        $model->status = 4;
        $model->finance_auditor = Auth::id();
        $model->finance_audit_at = date('Y-m-d H:i:s');
        if (!$model->save()) {
            DB::rollback();
            throw new CustomException('操作失败');
        }

        // 解冻
        Asset::unfreeze($model->fee, 42, $model->no, '拒绝提现', $model->user_id, Auth::user()->id, $model); // 解冻

        DB::commit();
        return true;
    }

    // 单号回填
    public static function orderIdBackfill($id, $externalOrderId, $fromAccount, $withdrawalMoney, $remark)
    {
        if (config('app.platform_id') != 1) {
            throw new CustomException('此功能未开启');
        }

        // 验证支付宝单号是否存在
        if (UserWithdrawOrder::where('external_order_id', $externalOrderId)->count('id')) {
            throw new CustomException('支付宝单号已存在');
        }

        DB::beginTransaction();

        $model = self::find($id);
        if ($model->status != 3) {
            throw new CustomException('状态不正确');
        }

        $model->status = 5;
        $model->external_order_id = $externalOrderId;
        $model->order_id_backfiller = Auth::id();
        $model->order_id_backfill_at = date('Y-m-d H:i:s');
        $model->real_fee = $withdrawalMoney;
        if ($remark) {
            $model->remark = $remark;
        }

        $model->save();

        // 提现
        Asset::withdraw($withdrawalMoney, 22, $model->no, '提现成功', $model->user_id, Auth::user()->id, $model);

        // 调提现接口
        $res = app(AssetApi::class)->manualWithdrawal(
            $model->no,
            $externalOrderId,
            $model->pay_type,
            $fromAccount,
            $withdrawalMoney,
            $model->receive_account,
            $model->name,
            $model->receive_account_type,
            $model->remark,
            'True'
        );

        // Code: 1.成功 2.失败 3.不存在订单号 4.订单已使用 5.提款信息不一致
        if ($res->Code != 1) {
            DB::rollback();
            throw new CustomException($res->Message);
        }

        DB::commit();
        return true;
    }

    // 自动转账
    public static function autoTransfer($id, $fromAccount, $remark, $adminId = null)
    {
        if (config('app.platform_id') != 1) {
            throw new CustomException('此功能未开启');
        }

        if ($adminId === null) {
            $adminId = Auth::id();
        }

        DB::beginTransaction();

        $model = self::find($id);
        if ($model->status != 3) {
            throw new CustomException('状态不正确');
        }

        // 更新提现类型
        $model->pay_type = 3; // 3.自动打款
        $model->status = 5;
        $model->order_id_backfiller = $adminId;
        $model->order_id_backfill_at = date('Y-m-d H:i:s');
        $model->real_fee = $model->fee;
        if ($remark) {
            $model->remark = $remark;
        }

        // 如果是生产环境
        if (config('app.env') == 'production') {
            // 调自动转账接口
            $res = app(AssetApi::class)->autoWithdrawal(
                $model->no,
                $model->pay_type,
                $fromAccount,
                $model->fee,
                $model->receive_account,
                $model->name,
                $model->receive_account_type,
                $model->remark,
                'True'
            );

            // Code: 1.成功 2.失败 3.不存在订单号 4.订单已使用 5.提款信息不一致
            if ($res->Code != 1) {
                DB::rollback();
                throw new CustomException($res->Message);
            }

            // 回填单号
            $model->external_order_id = $res->Data; // Data 里是支付宝单号
        }

        $model->save();

        // 提现
        Asset::withdraw($model->fee, 22, $model->no, '自动提现成功', $model->user_id, $adminId, $model);

        DB::commit();
        return true;
    }

    // 福禄提现申请
    public static function fulu($id)
    {
        if (config('app.platform_id') != 2) {
            throw new CustomException('此功能未开启');
        }

        DB::beginTransaction();

        $model = self::find($id);
        if ($model->status != 3) {
            throw new CustomException('状态不正确');
        }

        // 向福禄接口发起提现申请
        $billId = FuluFinance::pushBillStatement(
            $model->id,
            $model->fee,
            $model->receive_account,
            $model->name,
            $model->trustee,
            $model->acc_type,
            FuluFinance::settleType(config('user.trading_account.type')[$model->receive_account_type])
        );

        // 更新提现类型
        $model->pay_type          = 4; // 4.福禄管家打款
        $model->status            = 6; // 6.办款中
        $model->external_order_id = $billId; // 外部单号
        $model->save();

        DB::commit();
    }

    // 查看提现信息
    public static function fuluInfo($id)
    {
        $model = self::find($id);
        if (!in_array($model->status, [7, 8])) {
            throw new CustomException('状态不正确');
        }

        $fuluWithdrawNotify = FuluWithdrawNotify::where('bill_id', $model->external_order_id)->first();
        if (!$fuluWithdrawNotify) {
            throw new CustomException('尚无办款信息');
        }

        return $fuluWithdrawNotify;
    }
}
