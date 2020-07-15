<?php

namespace App\Http\Controllers\Home\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Home\UserAssetDailyRepository;

class AssetDailyController extends Controller
{
    public function index(Request $request)
    {
        $dateStart = $request->date_start;
        $dateEnd   = $request->date_end;

        $dataList = UserAssetDailyRepository::getList($dateStart, $dateEnd);
        return view('home.finance.asset-daily.index', compact('dataList', 'dateStart', 'dateEnd'));
    }
}
