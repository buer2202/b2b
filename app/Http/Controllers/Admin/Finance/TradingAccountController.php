<?php

namespace App\Http\Controllers\Admin\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\UserTradingAccountRepository;
use App\Exceptions\CustomException;

// 结算账号
class TradingAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataList = UserTradingAccountRepository::getList($request->user_id, $request->bank_card_no);
        $userTradingAccount = config('user.trading_account');

        return view('admin.finance.trading-account.index', compact('dataList', 'userTradingAccount'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            UserTradingAccountRepository::destroy($id);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
