<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceGroupUser extends Model
{
    public function priceGroup()
    {
        return $this->belongsTo(PriceGroup::class);
    }
}
