<?php

namespace App\Models;

use App\Models\Traits\GoodsGroupPrice;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    use GoodsGroupPrice;

    public function goodsCategory()
    {
        return $this->belongsTo(GoodsCategory::class);
    }
}
