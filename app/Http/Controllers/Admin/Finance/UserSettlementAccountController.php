<?php

namespace App\Http\Controllers\Admin\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\UserSettlementAccountRepository;
use App\Exceptions\CustomException;

// 结算账号
class UserSettlementAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataList = UserSettlementAccountRepository::getList($request->user_id, $request->bank_card_no);
        $config = config('asset.settlement_account');
        return view('admin.finance.user-settlement-account.index', compact('dataList', 'config'));
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
            UserSettlementAccountRepository::destroy($id);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
