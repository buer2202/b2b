<?php

namespace App\Http\Controllers\Admin\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\UserWithdrawOrderRepository;
use App\Exceptions\CustomException;
use Illuminate\Validation\Rule;

class UserWithdrawController extends Controller
{
    public function index(Request $request)
    {
        $dataList = UserWithdrawOrderRepository::getList($request->no, $request->user_id, $request->status);
        $status = config('asset.withdraw');
        $userTradingAccount = config('user.trading_account');
        $defaultFromAccount = config('asset.api_alipay_account')[0];

        return view('admin.finance.user-withdraw.index', compact('dataList', 'status', 'userTradingAccount', 'defaultFromAccount'));
    }

    // 部门审核
    public function department($id)
    {
        try {
            UserWithdrawOrderRepository::department($id);
        }
        catch(CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 财务审核
    public function finance($id)
    {
        try {
            UserWithdrawOrderRepository::finance($id);
        }
        catch(CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 拒绝
    public function refuse($id)
    {
        try {
            UserWithdrawOrderRepository::refuse($id);
        }
        catch(CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 回填单号
    public function orderIdBackfill($id, Request $request)
    {
        $this->validate($request, [
            'external_order_id' => 'bail|required|numeric:20,50',
            'from_account'      => ['required', Rule::in(config('asset.api_alipay_account'))],
            'amount'            => 'bail|required|numeric|min:0.01|max:1000000',
        ]);

        try {
            UserWithdrawOrderRepository::orderIdBackfill(
                $id,
                $request->external_order_id,
                $request->from_account,
                $request->amount,
                $request->remark
            );
        }
        catch(CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 自动转账
    public function autoTransfer($id, Request $request)
    {
        $this->validate($request, ['from_account' => ['required', Rule::in(config('asset.api_alipay_account'))]]);

        try {
            UserWithdrawOrderRepository::autoTransfer($id, $request->from_account, $request->remark);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 福禄提现
    public function fulu($id)
    {
        try {
            UserWithdrawOrderRepository::fulu($id);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 查看转账信息
    public function fuluInfo($id)
    {
        try {
            $res = UserWithdrawOrderRepository::fuluInfo($id);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $res);
    }
}
