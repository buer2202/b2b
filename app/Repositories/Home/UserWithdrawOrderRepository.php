<?php
namespace App\Repositories\Home;

use DB;
use Auth;
use App\Models\UserWithdrawOrder;
use App\Exceptions\CustomException;
use App\Models\UserSettlementAccount;
use Asset;
use Carbon\Carbon;

class UserWithdrawOrderRepository
{
    public static function getList($timeStart, $timeEnd, $status, $pageSize = 20)
    {
        $dataList = UserWithdrawOrder::where('user_id', Auth::user()->id)
            ->when(!empty($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(!empty($timeStart), function ($query) use ($timeStart) {
                return $query->where('created_at', '>=', $timeStart);
            })
            ->when(!empty($timeEnd), function ($query) use ($timeEnd) {
                return $query->where('created_at', '<=', Carbon::parse($timeEnd)->endOfDay());
            })
            ->orderBy('id', 'desc')
            ->when($pageSize === 0, function ($query) {
                return $query->limit(10000)->get();
            })
            ->when($pageSize, function ($query) use ($pageSize) {
                return $query->paginate($pageSize);
            });

        return $dataList;
    }

    /**
     * 申请提现
     * @return mixed
     */
    public static function store($accountId, $fee)
    {
        DB::beginTransaction();

        // 先查询结算账号
        $userSettlementAccount = UserSettlementAccount::where('user_id', Auth::id())->find($accountId);

        $fee = round($fee, 2); // 保留2位小数
        $withdrawNo = generate_order_no();
        $userId = Auth::id();

        // 创建提现单
        $withdraw = new UserWithdrawOrder;
        $withdraw->no                   = $withdrawNo;
        $withdraw->status               = 1;
        $withdraw->fee                  = $fee;
        $withdraw->user_id              = $userId;
        $withdraw->remark               = '用户申请提现';
        $withdraw->from_account         = '';
        $withdraw->trustee              = $userSettlementAccount->trustee;
        $withdraw->receive_account      = $userSettlementAccount->account;
        $withdraw->receive_account_type = $userSettlementAccount->type;
        $withdraw->name                 = $userSettlementAccount->name;
        $withdraw->acc_type             =  $userSettlementAccount->acc_type;
        if (!$withdraw->save()) {
            DB::rollback();
            throw new CustomException('申请失败');
        }

        // 资产冻结
        Asset::freeze($fee, 31, $withdrawNo, '用户申请提现', $userId, 0, $withdraw); // 冻结

        DB::commit();
        return true;
    }
}
