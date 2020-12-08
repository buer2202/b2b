<?php

namespace App\Http\Controllers\Admin\GoodsPrice;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\PriceGroupRepository;

class PriceGroupController extends Controller
{
    public function index(Request $request)
    {
        $dataList = PriceGroupRepository::getList($request->goods_model, $request->name);
        $config = config('price_group.goods_model');
        return view('admin.goods-price.price-group.index', compact('dataList', 'config'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'goods_model' => 'bail|required',
            'name'        => 'bail|required',
        ]);

        try {
            PriceGroupRepository::store($request->goods_model, $request->name);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'bail|required',
        ]);

        try {
            PriceGroupRepository::update($id, $request->name);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }
        return response()->ajax(1);
    }

    public function searchUser(Request $request)
    {
        if (!$request->filled('goods_model')) {
            return response()->ajax(0, '请先选择业务');
        }

        $rslt = PriceGroupRepository::getGroupUserByUserId($request->goods_model, $request->user_id);
        if (!$rslt) {
            return response()->ajax(0, '用户不存在或不在组中');
        }

        $respData = [
            'url'        => route('admin.goods-price.price-group-user.inside', ['groupId' => $rslt->price_group_id, 'search_key' => $request->user_id]),
            'group_id'   => $rslt->price_group_id,
            'group_name' => $rslt->priceGroup->name,
        ];

        return response()->ajax(1, 'success', $respData);
    }
}
