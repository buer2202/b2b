<?php

namespace App\Models\Traits;

use App\Models\PriceGroupGoods;

// 商品的组售价
trait GoodsGroupPrice
{
    // 商品在某个组中的价格
    public function priceGroupGoods()
    {
        return $this->hasOne(PriceGroupGoods::class, 'goods_id');
    }
}
