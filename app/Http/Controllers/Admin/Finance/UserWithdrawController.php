<?php

namespace App\Http\Controllers\Admin\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\UserWithdrawOrderRepository;
use App\Exceptions\CustomException;

class UserWithdrawController extends Controller
{
    public function index(Request $request)
    {
        $dataList = UserWithdrawOrderRepository::getList($request->no, $request->user_id, $request->status);
        $config = config('asset');
        return view('admin.finance.user-withdraw.index', compact('dataList', 'config'));
    }

    // 部门审核
    public function department($id)
    {
        try {
            UserWithdrawOrderRepository::department($id);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 财务审核
    public function finance($id)
    {
        try {
            UserWithdrawOrderRepository::finance($id);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 拒绝
    public function refuse($id)
    {
        try {
            UserWithdrawOrderRepository::refuse($id);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 线下提现
    public function offlinePay($id, Request $request)
    {
        try {
            UserWithdrawOrderRepository::offlinePay($id, $request->external_order_id, $request->remark);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
