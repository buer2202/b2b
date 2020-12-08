<?php

namespace App\Http\Controllers\Admin\Statement;

use App\Exports\Admin\PlatformAssetDaily;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\PlatformAssetDailyRepository;

class PlatformAssetDailyController extends Controller
{
    public function index(Request $request)
    {
        $dataList = PlatformAssetDailyRepository::getList($request->start_time, $request->end_time);
        return view('admin.statement.platform-asset-daily.index', compact('dataList'));
    }

    public function export(Request $request)
    {
        return (new PlatformAssetDaily($request))->download('平台资产日报 ' . date('Y-m-d H:i:s') . '.xlsx');
    }
}
