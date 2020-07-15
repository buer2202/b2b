<?php

namespace App\Http\Controllers\Home\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Home\UserWithdrawOrderRepository;
use App\Repositories\Home\UserTradingAccountRepository;
use App\Exceptions\CustomException;
use Auth;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataList = UserWithdrawOrderRepository::getList($request->time_start, $request->time_end, $request->status);
        $assetWithdrawStatus = config('asset.withdraw.status');
        $tradingAccounts = UserTradingAccountRepository::getList(1);
        return view('home.finance.withdraw.index', compact('dataList', 'assetWithdrawStatus', 'tradingAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'account_id' => 'required|integer',
            'fee'        => 'required|numeric|min:0.1',
        ]);

        try {
            if (!in_array(Auth::id(), config('asset.finance_withdraw_user_ids'))) {
                if ($request->fee > 50000) {
                    throw new CustomException('单笔提现不能超过5万元');
                }
            }

            UserWithdrawOrderRepository::store($request->account_id, $request->fee);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
