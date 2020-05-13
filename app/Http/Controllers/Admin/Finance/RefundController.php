<?php

namespace App\Http\Controllers\Admin\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\RefundOrderRepository;
use App\Exceptions\CustomException;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $dataList = RefundOrderRepository::getList($request->no, $request->user_id, $request->status);
        $status = config('asset.manual.refund');

        return view('admin.finance.refund.index', compact('dataList', 'status'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'bail|required|integer|min:0',
            'fee'     => 'bail|required|numeric|min:0',
            'remark'  => 'bail|required|string',
        ]);

        try {
            RefundOrderRepository::store($request->user_id, $request->fee, $request->remark);
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
            RefundOrderRepository::agree($id);
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
            RefundOrderRepository::refuse($id);
        }
        catch(CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
