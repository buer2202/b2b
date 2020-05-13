<?php

namespace App\Http\Controllers\Admin\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\DeductMoneyOrderRepository;
use App\Exceptions\CustomException;

// 扣款管理
class DeductMoneyController extends Controller
{
    public function index(Request $request)
    {
        $dataList = DeductMoneyOrderRepository::getList($request->no, $request->user_id, $request->status);
        $status = config('asset.manual.deduct-money');

        return view('admin.finance.deduct-money.index', compact('dataList', 'status'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'bail|required|integer|min:0',
            'fee'     => 'bail|required|numeric|min:0',
            'remark'  => 'bail|required|string',
        ]);

        try {
            DeductMoneyOrderRepository::store($request->user_id, $request->fee, $request->remark);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 同意
    public function agree($id)
    {
        try {
            DeductMoneyOrderRepository::agree($id);
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
            DeductMoneyOrderRepository::refuse($id);
        }
        catch(CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
