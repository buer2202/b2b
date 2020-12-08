<?php

namespace App\Http\Controllers\Admin\GoodsPrice;

use App\Exceptions\CustomException;
use App\Repositories\Admin\GoodsCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsCategoryController extends Controller
{
    public function index(Request $request)
    {
        $dataList = GoodsCategoryRepository::getList($request->name);

        return view('admin.goods-price.goods-category.index', compact('dataList'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'no'      => 'bail|required|string',
            'name'    => 'bail|required|string',
            'sortord' => 'bail|required|integer',
            'status'  => 'bail|required|in: 0,1',
        ]);

        try {
            GoodsCategoryRepository::store($request->no, $request->name, $request->sortord, $request->status);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    public function show($id)
    {
        try {
            $data = GoodsCategoryRepository::find($id);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'no'      => 'bail|required|string',
            'name'    => 'bail|required|string',
            'sortord' => 'bail|required|integer',
            'status'  => 'bail|required|in: 0,1',
        ]);

        try {
            GoodsCategoryRepository::update($id, $request->no, $request->name, $request->sortord, $request->status);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
