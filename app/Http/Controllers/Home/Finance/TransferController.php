<?php

namespace App\Http\Controllers\Home\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Home\UserTransferOrderRepository;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataList = UserTransferOrderRepository::getList($request->time_start, $request->time_end, $request->status);
        $status = config('asset.transfer.order_status');

        return view('home.finance.transfer.index', compact('dataList', 'status'));
    }
}
