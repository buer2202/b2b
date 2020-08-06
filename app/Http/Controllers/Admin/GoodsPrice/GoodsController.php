<?php

namespace App\Http\Controllers\Admin\GoodsPirce;

use App\Exceptions\CustomException;
use App\Repositories\Admin\GoodsCategoryRepository;
use App\Repositories\Admin\GoodsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    public function index(Request $request)
    {
        $dataList = GoodsRepository::getList($request->category_id, $request->km_goods_id, $request->name);
        $categories = GoodsCategoryRepository::getList();
        
        return view('admin.goods.km-goods.index', compact('dataList', 'categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'goods_category_id' => 'bail|required',
            'name'              => 'bail|required',
            'face_value'        => 'bail|required',
            'status'            => 'bail|required',
        ]);

        try {
            GoodsRepository::store($request->goods_category_id, $request->name, $request->face_value, $request->status);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    public function show($id)
    {
        try {
            $channel = GoodsRepository::find($id);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $channel);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'goods_category_id' => 'bail|required',
            'name'              => 'bail|required',
            'face_value'        => 'bail|required',
            'status'            => 'bail|required',
        ]);

        try {
            GoodsRepository::update($id, $request->goods_category_id, $request->name, $request->face_value, $request->status);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}