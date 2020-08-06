<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceGroup extends Model
{
    // 组内用户
    public function priceGroupUsers()
    {
        return $this->belongsToMany(User::class, 'price_group_users');
    }

    public function priceGroupGoods()
    {
        return $this->belongsToMany($this->goods_model, 'price_group_goods', null, 'goods_id')->withPivot('id', 'cost_price', 'sales_price');
    }
}
