<?php

namespace App\Http\Controllers\Admin\GoodsPrice;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\PriceGroupRepository;

class PriceGroupGoodsController extends Controller
{
    // 组内商品管理
    public function inside($groupId, Request $request)
    {
        $group = PriceGroupRepository::find($groupId);
        $dataList = PriceGroupRepository::inGroupGoods($group, $request->goods_name);
        $config = config('price_group.goods_model');
        return view('admin.goods-price.price-group-goods.inside', compact('group', 'dataList', 'config'));
    }

    // 组外商品管理
    public function outside($groupId, Request $request)
    {
        $group = PriceGroupRepository::find($groupId);
        $dataList = PriceGroupRepository::outGroupGoods($group, $request->goods_name);
        return view('admin.goods-price.price-group-goods.outside', compact('group', 'dataList'));
    }

    // 添加商品
    public function add($groupId, Request $request)
    {
        try {
            if (!$request->filled('goods')) {
                throw new CustomException('请先勾选商品');
            }

            PriceGroupRepository::addGoods($groupId, $request->goods);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 删除商品
    public function delete($groupId, Request $request)
    {
        try {
            if (!$request->filled('users')) {
                throw new CustomException('请先勾选商品');
            }

            PriceGroupRepository::deleteUsers($groupId, $request->users);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 移动商品
    public function move($groupId, Request $request)
    {
        try {
            if (!$request->filled('users')) {
                throw new CustomException('请先勾选商品');
            }

            PriceGroupRepository::moveUsers($groupId, $request->new_group_id, $request->users);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 编辑价格
    public function editPrice($priceGroupGoodsId)
    {
        try {
            $data = PriceGroupRepository::priceGroupGoods($priceGroupGoodsId);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }
        return response()->ajax(1, 'success', $data);
    }

    // 变价
    public function updatePrice($priceGroupGoodsId, Request $request)
    {
        try {
            PriceGroupRepository::updatePrice($priceGroupGoodsId, $request->cost_price, $request->sales_price);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }
        return response()->ajax(1);
    }
}
