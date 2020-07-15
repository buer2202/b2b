<?php

namespace App\Http\Controllers\Home\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Home\UserAmountFlowRepository;

class AmountFlowController extends Controller
{
    public function index(Request $request)
    {
        $tradeNo   = trim($request->trade_no);
        $tradeType = $request->trade_type;
        $timeStart = $request->time_start;
        $timeEnd   = $request->time_end;
        $dataList = UserAmountFlowRepository::getList($tradeNo, $tradeType, $timeStart, $timeEnd);
        $assetTradeTypeUser = config('asset.trade_type.user');

        return view('home.finance.amount-flow.index', compact('dataList', 'tradeNo', 'tradeType', 'timeStart', 'timeEnd', 'assetTradeTypeUser'));
    }
}
