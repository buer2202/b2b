<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceGroupGoods extends Model
{
    public function priceGroup()
    {
        return $this->belongsTo(PriceGroup::class);
    }

    // 获取关联商品（动态）
    public function goods()
    {
        return $this->belongsTo($this->priceGroup->goods_model, 'goods_id');
    }
}
