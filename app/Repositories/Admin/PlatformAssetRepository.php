<?php
namespace App\Repositories\Admin;

use Buer\Asset\Models\PlatformAsset;

class PlatformAssetRepository
{
    public static function get()
    {
        $model = PlatformAsset::find(1);

        return $model;
    }
}
