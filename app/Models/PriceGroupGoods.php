<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceGroupGoods extends Model
{
    public $goodsModel;

    public function priceGroup()
    {
        return $this->belongsTo(PriceGroup::class);
    }
}
