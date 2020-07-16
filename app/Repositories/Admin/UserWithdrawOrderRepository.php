<?php
namespace App\Repositories\Admin;

use App\Models\UserWithdrawOrder;
use App\Exceptions\CustomException;
use DB;
use Asset;
use Auth;

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
        Asset::unfreeze($model->fee, 41, $model->no, '拒绝提现', $model->user_id, Auth::user()->id, $model); // 解冻

        DB::commit();
        return true;
    }

    public static function offlinePay($id, $externalOrderId, $remark)
    {
        DB::beginTransaction();

        $model = self::find($id);
        if ($model->status != 3) {
            throw new CustomException('状态不正确');
        }

        // 更新提现类型
        $model->pay_type = 1; // 1.线下打款 2.线上打款
        $model->status = 5;
        $model->real_fee = $model->fee;
        $model->external_order_id = $externalOrderId;
        $model->remark = $remark;
        $model->save();

        // 提现
        Asset::withdraw($model->fee, 22, $model->no, '自动提现成功', $model->user_id, Auth::id(), $model);

        DB::commit();
        return true;
    }
}
