<?php

namespace App\Repositories\Admin;

use App\Models\GoodsCategory;
use App\Exceptions\CustomException;
use Illuminate\Database\QueryException;

class GoodsCategoryRepository
{
    public static function getList($name = '')
    {
        $dataList = GoodsCategory::orderBy('sortord')
            ->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->get();

        return $dataList;
    }

    public static function store($no, $name, $sortord, $status)
    {
        $model = new GoodsCategory;
        $model->no      = $no;
        $model->name    = $name;
        $model->sortord = $sortord;
        $model->status  = $status;
        try {
            $model->save();
        } catch (QueryException $e) {
            throw new CustomException('数据写入失败');
        }
        return true;
    }

    public static function find($id)
    {
        $data = GoodsCategory::find($id);
        if (empty($data)) {
            throw new CustomException('数据不存在');
        }
        return $data;
    }

    public static function update($id, $no, $name, $sortord, $status)
    {
        $model = self::find($id);
        $model->no      = $no;
        $model->name    = $name;
        $model->sortord = $sortord;
        $model->status  = $status;
        try {
            $model->save();
        } catch (QueryException $e) {
            throw new CustomException('数据更新失败');
        }
        return true;
    }
}
