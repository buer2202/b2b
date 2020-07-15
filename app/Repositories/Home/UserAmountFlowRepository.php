<?php
namespace App\Repositories\Home;

use Auth;
use Buer\Asset\Models\UserAmountFlow;
use Carbon\Carbon;

class UserAmountFlowRepository
{
    public static function getList($tradeNo, $tradeType, $timeStart, $timeEnd, $pageSize = 20)
    {
        $dataList = UserAmountFlow::where('user_id', Auth::user()->id)
            ->when(!empty($tradeNo), function ($query) use ($tradeNo) {
                return $query->where('trade_no', $tradeNo);
            })
            ->when(!empty($tradeType), function ($query) use ($tradeType) {
                return $query->where('trade_type', $tradeType);
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
}
