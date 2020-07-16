<?php
namespace App\Repositories\Home;

use App\Exceptions\CustomException;
use App\Models\UserSettlementAccount;
use Auth;

class UserSettlementAccountRepository
{
    public static function getList($status = '')
    {
        $dataList = UserSettlementAccount::where('user_id', Auth::user()->id)
            ->when($status !== '', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->get();

        return $dataList;
    }

    public static function store($type, $trustee, $account, $name, $accType)
    {
        $count = UserSettlementAccount::where('user_id', Auth::id())->count('id');
        if ($count > 10) {
            throw new CustomException('最多创建10个结算账号');
        }

        $model = new UserSettlementAccount;
        $model->user_id  = Auth::id();
        $model->type     = $type;
        $model->trustee  = $trustee;
        $model->account  = $account;
        $model->name     = $name;
        $model->status   = 1;
        $model->acc_type = $accType;

        if (!$model->save()) {
            throw new CustomException('数据创建失败');
        }

        return true;
    }
}
