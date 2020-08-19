<?php

namespace App\Repositories\Home;

use App\Models\PriceGroupUser;
use Auth;

class PriceGroupGoodsRepository
{
    // 获取通用商品列表
    public static function goodsList($goodsModeName)
    {
        $priceGroupId = PriceGroupUser::where('user_id', Auth::id())
            ->where('goods_model', $goodsModeName)
            ->value('price_group_id');

        $goodsList = $goodsModeName::orderBy('name')
            ->where('status', 1)
            ->whereHas('priceGroupGoods', function ($query) use ($priceGroupId) {
                $query->where('price_group_id', $priceGroupId);
            })
            ->with(['priceGroupGoods' => function ($query) use ($priceGroupId) {
                $query->where('price_group_id', $priceGroupId);
            }])
            ->paginate(20);

        return $goodsList;
    }
}
