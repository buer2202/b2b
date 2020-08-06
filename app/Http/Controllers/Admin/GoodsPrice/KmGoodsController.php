<?php

namespace App\Http\Controllers\Admin\GoodsPirce;

use App\Exceptions\CustomException;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\KmGoodsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KmGoodsController extends Controller
{
    public function index(Request $request)
    {
        $dataList = KmGoodsRepository::getList($request->category_id, $request->km_goods_id, $request->name);
        $categories = CategoryRepository::getList();

        return view('admin.goods.km-goods.index', compact('dataList', 'categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'bail|required',
            'km_goods_id' => 'bail|required',
            'name' => 'bail|required',
            'parvalue' => 'bail|required',
            'status' => 'bail|required',
        ]);

        try {
            KmGoodsRepository::store(
                $request->category_id,
                $request->km_goods_id,
                $request->name,
                $request->parvalue,
                $request->status
            );
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    public function show($id)
    {
        try {
            $channel = KmGoodsRepository::find($id);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $channel);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category_id' => 'bail|required',
            'km_goods_id' => 'bail|required',
            'name' => 'bail|required',
            'parvalue' => 'bail|required',
            'status' => 'bail|required',
        ]);

        try {
            KmGoodsRepository::update(
                $id,
                $request->category_id,
                $request->km_goods_id,
                $request->name,
                $request->parvalue,
                $request->status
            );
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
