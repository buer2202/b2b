<?php
namespace App\Repositories\Home;

use Auth;
use Buer\Asset\Models\UserAssetDaily;

class UserAssetDailyRepository
{
    public static function getList($dateStart, $dateEnd, $pageSize = 20)
    {
        $dataList = UserAssetDaily::where('user_id', Auth::user()->id)
            ->when(!empty($dateStart), function ($query) use ($dateStart) {
                return $query->where('date', '>=', $dateStart);
            })
            ->when(!empty($dateEnd), function ($query) use ($dateEnd) {
                return $query->where('date', '<=', $dateEnd);
            })
            ->orderBy('date', 'desc')
            ->when($pageSize === 0, function ($query) {
                return $query->limit(10000)->get();
            })
            ->when($pageSize, function ($query) use ($pageSize) {
                return $query->paginate($pageSize);
            });

        return $dataList;
    }
}
