<?php
namespace App\Repositories\Admin;

use Carbon\Carbon;
use App\Exceptions\CustomException;
use Buer\Asset\Models\UserAmountFlow;
use DB;

class UserAmountFlowRepository
{
    public static function getList($userId, $tradeNo, $tradeType, $tradeSubtype, $timeStart, $timeEnd, $pageSize = 20)
    {
        $dataList = UserAmountFlow::orderBy('id', 'desc')
            ->when(!empty($userId), function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when(!empty($tradeNo), function ($query) use ($tradeNo) {
                return $query->where('trade_no', $tradeNo);
            })
            ->when(!empty($tradeType), function ($query) use ($tradeType) {
                return $query->where('trade_type', $tradeType);
            })
            ->when(!empty($tradeSubtype), function ($query) use ($tradeSubtype) {
                return $query->where('trade_subtype', $tradeSubtype);
            })
            ->when(!empty($timeStart), function ($query) use ($timeStart) {
                return $query->where('created_at', '>=', $timeStart);
            })
            ->when(!empty($timeEnd), function ($query) use ($timeEnd) {
                return $query->where('created_at', '<=', $timeEnd);
            })
            ->when($pageSize === 0, function ($query) {
                return $query->limit(10000)->get();
            })
            ->when($pageSize, function ($query) use ($pageSize) {
                return $query->paginate($pageSize);
            });

        return $dataList;
    }

    public static function statistics($userId, $startTime, $endTime)
    {
        $diffDayNum = Carbon::parse($startTime)->diffInDays(Carbon::parse($endTime));
        if ($diffDayNum > 100) {
            throw new CustomException('最多查询100天');
        }

        $dataList = UserAmountFlow::select('trade_type', 'trade_subtype', DB::raw('sum(fee) as total_fee'))
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($startTime, function ($query) use ($startTime) {
                return $query->where('created_at', '>=', $startTime);
            })
            ->when($endTime, function ($query) use ($endTime) {
                return $query->where('created_at', '<=', Carbon::parse($endTime)->endOfDay());
            })
            ->groupBy('trade_type', 'trade_subtype')
            ->get();

        return $dataList;
    }
}
