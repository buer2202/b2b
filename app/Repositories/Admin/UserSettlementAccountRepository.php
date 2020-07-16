<?php
namespace App\Repositories\Admin;

use App\Exceptions\CustomException;
use App\Models\UserSettlementAccount;

// 用户结算账号
class UserSettlementAccountRepository
{
    public static function getList($userId, $bankCardNo)
    {
        $dataList = UserSettlementAccount::orderBy('id', 'desc')
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($bankCardNo, function ($query) use ($bankCardNo) {
                return $query->where('bank_card_no', $bankCardNo);
            })
            ->paginate(20);

        return $dataList;
    }

    public static function destroy($id)
    {
        $model = UserSettlementAccount::find($id);
        if (!$model->delete()) {
            throw new CustomException('数据删除失败');
        }

        return true;
    }
}
