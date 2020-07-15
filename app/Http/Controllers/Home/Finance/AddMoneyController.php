<?php

namespace App\Http\Controllers\Home\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Home\UserAddMoneyOrderRepository;

// 加款
class AddMoneyController extends Controller
{
    public function index(Request $request)
    {
        $dataList = UserAddMoneyOrderRepository::getList($request->time_start, $request->time_end, $request->status);
        $status = config('asset.add-money');

        return view('home.finance.add-money.index', compact('dataList', 'status'));
    }
}
