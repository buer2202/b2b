<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Buer\Asset\Models\Relations\AssetAmountMorphMany;

class UserAddMoneyOrder extends Model
{
    use AssetAmountMorphMany;
}
