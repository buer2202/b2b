<?php

namespace App\Repositories\Admin;

use App\Models\Goods;
use App\Exceptions\CustomException;
use App\Models\GoodsCategory;
use Illuminate\Database\QueryException;

class GoodsRepository
{
    public static function getList($goodsCategoryId, $goodsId, $name)
    {
        $dataList = Goods::with('goodsCategory')
            ->orderBy('goods_category_id')->orderBy('name')
            ->when($goodsCategoryId, function ($query) use ($goodsCategoryId) {
                return $query->where('goods_category_id', $goodsCategoryId);
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

    public static function store($goodsCategoryId, $name, $faceValue, $status)
    {
        // 获取分类信息
        $category = GoodsCategory::find($goodsCategoryId);
        if (!$category) {
            throw new CustomException('分类ID不存在');
        }

        $model = new Goods;
        $model->goods_category_id = $goodsCategoryId;
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

    public static function update($id, $goodsCategoryId, $name, $faceValue, $status)
    {
        // 获取分类信息
        $category = GoodsCategory::find($goodsCategoryId);
        if (!$category) {
            throw new CustomException('分类ID不存在');
        }

        $model = self::find($id);
        $model->goods_category_id = $goodsCategoryId;
        $model->name              = $name;
        $model->face_value        = $faceValue;
        $model->status            = $status;
        try {
            $model->save();
        } catch (QueryException $e) {
            throw new CustomException($e->getMessage());
            throw new CustomException('数据更新失败');
        }
        return true;
    }
}
