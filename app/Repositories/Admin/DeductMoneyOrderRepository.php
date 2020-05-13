<?php
namespace App\Repositories\Admin;

use App\Models\DeductMoneyOrder;
use App\Exceptions\CustomException;
use Auth;
use DB;
use Asset;
use Carbon\Carbon;

class DeductMoneyOrderRepository
{
    public static function getList($no, $userId, $status)
    {
        $dataList = DeductMoneyOrder::orderBy('id', 'desc')
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
        $model = DeductMoneyOrder::lockForUpdate()->find($id);
        if (empty($model)) {
            throw new CustomException('数据不存在');
        }

        return $model;
    }

    public static function store($userId, $fee, $remark)
    {
        // 创建加款单
        $model = new DeductMoneyOrder;
        $model->no         = generate_order_no();
        $model->status     = 1;
        $model->fee        = round($fee, 2);
        $model->user_id    = $userId;
        $model->created_by = Auth::user()->id;
        $model->remark     = $remark;

        if (!$model->save()) {
            throw new CustomException('数据创建失败');
        }

        return true;
    }

    // 同意
    public static function agree($id)
    {
        DB::beginTransaction();

        $model = self::find($id);
        if ($model->status != 1) {
            throw new CustomException('状态不正确');
        }

        $model->status = 2;
        $model->auditd_by = Auth::user()->id;
        $model->auditd_at = Carbon::now();
        if (!$model->save()) {
            DB::rollback();
            throw new CustomException('操作失败');
        }

        // 扣款
        Asset::consume($model->fee, 57, $model->no, '手动扣款', $model->user_id, Auth::user()->id, $model);

        DB::commit();
        return true;
    }

    // 拒绝
    public static function refuse($id)
    {
        DB::beginTransaction();

        $model = self::find($id);
        $model->auditd_by = Auth::user()->id;
        $model->auditd_at = Carbon::now();
        if ($model->status != 1) {
            throw new CustomException('状态不正确');
        }

        $model->status = 3;
        if (!$model->save()) {
            DB::rollback();
            throw new CustomException('操作失败');
        }

        DB::commit();
        return true;
    }
}
