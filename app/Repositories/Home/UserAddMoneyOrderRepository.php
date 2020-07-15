<?php

namespace App\Repositories\Home;

use App\Models\UserAddMoneyOrder;
use Auth;
use Carbon\Carbon;

class UserAddMoneyOrderRepository
{
    public static function getList($timeStart, $timeEnd, $status, $pageSize = 20)
    {
        $dataList = UserAddMoneyOrder::where('user_id', Auth::id())
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
}
