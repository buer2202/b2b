<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    public function goodsCategory()
    {
        return $this->belongsTo(GoodsCategory::class);
    }
}
