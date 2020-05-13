<?php

namespace App\Http\Controllers\Admin\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\PlatformAmountFlowRepository;
use App\Exceptions\CustomException;
use App\Jobs\Exports\PlatformAmountFlowJob;
use Auth;

class PlatformAmountFlowController extends Controller
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

        $dataList = PlatformAmountFlowRepository::getList($userId, $tradeNo, $tradeType, $tradeSubtype, $startTime, $endTime);
        $assetTradeType = config('asset');

        return view('admin.finance.platform-amount-flow.index', compact('dataList', 'assetTradeType'));
    }

    public function export(Request $request)
    {
        $userId       = trim($request->user_id);
        $tradeNo      = trim($request->trade_no);
        $tradeType    = $request->trade_type;
        $tradeSubtype = $request->trade_subtype;
        $startTime    = $request->start_time;
        $endTime      = $request->end_time;

        // 分发队列任务
        PlatformAmountFlowJob::dispatch(
            gateway_group_id('admin'), $userId, $tradeNo, $tradeType, $tradeSubtype, $startTime, $endTime
        );

        return response()->ajax(1);
    }

    public function order($id)
    {
        try {
            $data = PlatformAmountFlowRepository::getOrder($id);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $data);
    }
}
