<?php

namespace App\Repositories\Admin;

use App\Models\Goods;
use App\Exceptions\CustomException;
use App\Models\GoodsCategory;
use Illuminate\Database\QueryException;

class GoodsRepository
{
    public static function getList($categoryId, $goodsId, $name)
    {
        $dataList = Goods::with('category')
            ->orderBy('goods_category_id')->orderBy('name')
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('goods_category_id', $categoryId);
            })
            ->when($goodsId, function ($query) use ($goodsId) {
                return $query->where('id', $goodsId);
            })
            ->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->paginate(20);

        return $dataList;
    }

    public static function store($categoryId, $name, $faceValue, $status)
    {
        // 获取分类信息
        $category = GoodsCategory::find($categoryId);
        if (!$category) {
            throw new CustomException('分类ID不存在');
        }

        $model = new Goods;
        $model->goods_category_id = $categoryId;
        $model->name              = $name;
        $model->face_value        = $faceValue;
        $model->status            = $status;
        try {
            $model->save();
        } catch (QueryException $e) {
            throw new CustomException('数据写入失败');
        }
        return true;
    }

    public static function find($id)
    {
        $model = Goods::find($id);
        if (empty($model)) {
            throw new CustomException('数据不存在');
        }
        return $model;
    }

    public static function update($id, $categoryId, $name, $faceValue, $status)
    {
        // 获取分类信息
        $category = GoodsCategory::find($categoryId);
        if (!$category) {
            throw new CustomException('分类ID不存在');
        }

        $model = self::find($id);
        $model->category_id = $categoryId;
        $model->name        = $name;
        $model->face_value  = $faceValue;
        $model->status      = $status;
        try {
            $model->save();
        } catch (QueryException $e) {
            throw new CustomException('数据更新失败');
        }
        return true;
    }
}
