<?php
namespace App\Repositories\Admin;

use App\Models\UserAddMoneyOrder;
use App\Exceptions\CustomException;
use Auth;
use DB;
use Asset;
use Carbon\Carbon;

class UserAddMoneyOrderRepository
{
    public static function getList($startTime, $endTime, $no, $userId, $status, $isExport = false)
    {
        $dataList = UserAddMoneyOrder::orderBy('id', 'desc')
            ->when($startTime, function ($query) use ($startTime) {
                return $query->where('created_at', '>=', $startTime);
            })
            ->when($endTime, function ($query) use ($endTime) {
                return $query->where('created_at', '<=', Carbon::parse($endTime)->endOfDay());
            })
            ->when($no, function ($query) use ($no) {
                return $query->where('no', $no);
            })
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($isExport, function ($query) {
                return $query->get();
            })
            ->when(!$isExport, function ($query) {
                return $query->paginate(20);
            });

        return $dataList;
    }

    public static function find($id)
    {
        $model = UserAddMoneyOrder::lockForUpdate()->find($id);
        if (empty($model)) {
            throw new CustomException('数据不存在');
        }

        return $model;
    }

    public static function store($userId, $fee, $remark)
    {
        // 创建加款单
        $model = new UserAddMoneyOrder;
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

        // 加款
        Asset::recharge($model->fee, 11, $model->no, '加款成功', $model->user_id, Auth::user()->id, $model);

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
