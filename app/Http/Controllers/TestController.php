<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\User;
use App\Repositories\Home\PriceGroupGoodsRepository;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        // 通用商品列表获取方法
        $dataList = PriceGroupGoodsRepository::goodsList(Goods::class);
        dump($dataList);
    }
}
