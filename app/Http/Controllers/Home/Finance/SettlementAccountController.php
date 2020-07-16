<?php

namespace App\Http\Controllers\Home\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Home\UserSettlementAccountRepository;
use App\Exceptions\CustomException;

class SettlementAccountController extends Controller
{
    public function index(Request $request)
    {
        $dataList = UserSettlementAccountRepository ::getList();
        $config = config('asset.settlement_account');
        return view('home.finance.settlement-account.index', compact('dataList', 'config'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type'     => 'required|in:1,2',
            'trustee'  => 'required|string',
            'account'  => 'required|string',
            'name'     => 'required|string',
            'acc_type' => 'required|in:1,2'
        ]);

        try {
            UserSettlementAccountRepository::store(
                $request->type,
                $request->trustee,
                $request->account,
                $request->name,
                $request->acc_type
            );
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
