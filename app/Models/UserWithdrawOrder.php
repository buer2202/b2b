<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Buer\Asset\Models\Relations\AssetAmountMorphMany;

class UserWithdrawOrder extends Model
{
    use AssetAmountMorphMany;
}
