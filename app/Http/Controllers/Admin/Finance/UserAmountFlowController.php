<?php

namespace App\Http\Controllers\Admin\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\UserAmountFlowRepository;
use App\Exceptions\CustomException;

class UserAmountFlowController extends Controller
{
    public function index(Request $request)
    {
        $userId       = trim($request->user_id);
        $tradeNo      = trim($request->trade_no);
        $tradeType    = $request->trade_type;
        $tradeSubtype = $request->trade_subtype;
        $startTime    = $request->start_time;
        $endTime      = $request->end_time;
        $endTime      = !empty($endTime) ? $endTime . ' 23:59:59' : '';

        $dataList = UserAmountFlowRepository::getList($userId, $tradeNo, $tradeType, $tradeSubtype, $startTime, $endTime);
        $assetTradeType = config('asset');

        return view('admin.finance.user-amount-flow.index', compact('dataList', 'assetTradeType'));
    }

    public function statistics(Request $request)
    {
        $startTime = $request->input('start_time', date('Y-m-d'));
        $endTime = $request->input('end_time', date('Y-m-d'));

        try {
            $dataList = UserAmountFlowRepository::statistics($request->user_id, $startTime, $endTime);
        } catch (CustomException $e) {
            return back()->with(['alert' => $e->getMessage()]);
        }

        $tradeType = config('asset.trade_type');

        return view('admin.finance.user-amount-flow.statistics', compact('dataList', 'tradeType', 'startTime', 'endTime'));
    }
}
