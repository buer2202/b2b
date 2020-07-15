<?php

namespace App\Http\Controllers\Home\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Home\UserAddMoneyOrderRepository;
use App\Exceptions\CustomException;
use Auth;

// 加款
class AddMoneyController extends Controller
{
    public function index(Request $request)
    {
        $dataList = UserAddMoneyOrderRepository::getList($request->time_start, $request->time_end, $request->status);
        $status = config('asset.add-money');

        return view('home.finance.add-money.index', compact('dataList', 'status'));
    }

    // 二维码加款弹窗
    public function qrIframe()
    {
        // 如果是羊毛用户
        if (Auth::user()->isWool()) {
            $alipayAccount = config('asset.api_alipay_account')[1];
            $qrcodeUrl = '/images/qrcode/hfjp_zfb_skm.jpg';
        } else {
            $alipayAccount = config('asset.api_alipay_account')[0];
            $qrcodeUrl = '/images/qrcode/18056079958.jpg';
        }

        $companyName = '合肥锦鹏科技有限公司';
        $alipayOrderListPic = url('/images/qrcode/hfjp_alipay_order_list.jpg');
        $alipayOrderDetailPic = url('/images/qrcode/hfjp_alipay_order_detail.jpg');

        return view('home.finance.add-money.qr-iframe', compact('alipayAccount', 'qrcodeUrl', 'companyName', 'alipayOrderListPic', 'alipayOrderDetailPic'));
    }

    // 加款
    public function store(Request $request)
    {
        $this->validate($request, [
            'alipay_order_id' => 'bail|required|digits_between:20,50',
            'pay_money'       => 'bail|required|numeric|min:0.01|max:100000000',
            'audit_type'      => 'bail|required|in:1,2',
        ]);

        try {
            UserAddMoneyOrderRepository::store($request->alipay_order_id, 2, $request->pay_money, $request->audit_type);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
