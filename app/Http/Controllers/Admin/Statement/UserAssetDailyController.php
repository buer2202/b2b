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
        $dataList = UserAssetDailyRepository::getList($request->user_id, $request->start_time, $request->end_time);
        return view('admin.statement.user-asset-daily.index', compact('dataList'));
    }

    public function export(Request $request)
    {
        return (new UserAssetDaily($request))->download('用户资产日报' . date('Y-m-d H:i:s') . '.xlsx');
    }
}
