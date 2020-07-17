<?php

namespace App\Http\Controllers\Admin\Statement;

use App\Exports\Admin\UserAssetDaily;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\UserAssetDailyRepository;

class UserAssetDailyController extends Controller
{
    /**
     * @param Request $request
     * @param UserAssetDailyRepository $userAssetDailyRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $userId    = $request->user_id;
        $dateStart = $request->start_time;
        $dateEnd   = $request->end_time;
        $dataList = UserAssetDailyRepository::getList($userId, $dateStart, $dateEnd);
        return view('admin.statement.user-asset-daily.index', compact('dataList', 'userId', 'dateStart', 'dateEnd'));
    }

    public function export(Request $request)
    {
        return (new UserAssetDaily($request))->download('用户资产日报' . date('Y-m-d H:i:s') . '.xlsx');
    }
}
