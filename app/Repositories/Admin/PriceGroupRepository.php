<?php

namespace App\Repositories\Admin;

use App\Exceptions\CustomException;
use App\Models\PriceGroup;
use App\Models\PriceGroupGoods;
use App\Models\PriceGroupUser;
use App\Models\User;
use DB;
use Illuminate\Database\QueryException;

class PriceGroupRepository
{
    public static function getList($goodsModel, $name)
    {
        $dataList = PriceGroup::orderBy('goods_model')->orderBy('name')
            ->when($goodsModel, function ($query) use ($goodsModel) {
                return $query->where('goods_model', $goodsModel);
            })
            ->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->paginate(20);

        return $dataList;
    }

    public static function store($goodsModel, $name)
    {
        $model = new PriceGroup;
        $model->name        = $name;
        $model->goods_model = $goodsModel;
        try {
            $model->save();
        } catch (QueryException $e) {
            mylog('db_error', $e->getMessage());
            throw new CustomException('数据写入失败');
        }

        return true;
    }

    public static function find($id)
    {
        $data = PriceGroup::find($id);
        if (!$data) {
            throw new CustomException('数据不存在');
        }
        return $data;
    }

    public static function update($id, $name)
    {
        $model = self::find($id);
        $model->name = $name;
        $model->save();
        return true;
    }

    // 业务所有组
    public static function getByGoodsModel($goodsModel, $exceptId = null)
    {
        $dataList = PriceGroup::orderBy('name')
            ->where('goods_model', $goodsModel)
            ->when($exceptId, function ($query) use ($exceptId) {
                return $query->where('id', '<>', $exceptId);
            })
            ->get();
        return $dataList;
    }

    // 组内用户
    public static function inGroupUsers(PriceGroup $model, $searchKey = null)
    {
        $dataList = $model->priceGroupUsers()
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->where(function ($query) use ($searchKey) {
                    $query->where('users.id', $searchKey)
                        ->orWhere('users.real_name', 'like', "%{$searchKey}%")
                        ->orWhere('users.remark', 'like', "%{$searchKey}%");
                });
            })
            ->get();

        return $dataList;
    }

    // 组外用户
    public static function outGroupUsers($goodsModel, $searchKey = null)
    {
        $dataList = User::whereDoesntHave('priceGroupUsers', function ($query) use ($goodsModel) {
                $query->where('goods_model', $goodsModel);
            })
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->where(function ($query) use ($searchKey) {
                    $query->where('users.id', $searchKey)
                        ->orWhere('users.real_name', 'like', "%{$searchKey}%")
                        ->orWhere('users.remark', 'like', "%{$searchKey}%");
                });
            })
            ->get();
        return $dataList;
    }

    // 将用户添加到组
    public static function addUsers($id, $userIds)
    {
        $model = self::find($id);

        // 先验证用户是否没有组
        $alreadyHasGroupUserIds = PriceGroupUser::whereIn('user_id', $userIds)->where('goods_model', $model->goods_model)->pluck('user_id');
        if ($alreadyHasGroupUserIds->isNotEmpty()) {
            $hasIds = $alreadyHasGroupUserIds->implode(',');
            throw new CustomException("{$hasIds} 在{$model->goods_model}业务中有组！");
        }

        // 写入组用户
        $datetime = date('Y-m-d H:i:s');
        foreach ($userIds as $userId) {
            $insertData[] = [
                'user_id'        => $userId,
                'price_group_id' => $model->id,
                'goods_model'    => $model->goods_model,
                'created_at'     => $datetime,
                'updated_at'     => $datetime,
            ];
        }

        try {
            PriceGroupUser::insert($insertData);
        } catch (QueryException $e) {
            throw new CustomException('数据写入失败');
        }

        return true;
    }

    // 删除组用户
    public static function deleteUsers($id, $users)
    {
        $model = self::find($id);
        $model->priceGroupUsers()->detach($users);
        return true;
    }

    // 移动用户
    public static function moveUsers($originId, $newId, $users)
    {
        DB::beginTransaction();
        self::deleteUsers($originId, $users);
        self::addUsers($newId, $users);
        DB::commit();
        return true;
    }

    // 组内商品
    public static function inGroupGoods(PriceGroup $model, $goodsName = null)
    {
        $dataList = $model->priceGroupGoods()
            ->when($goodsName, function ($query) use ($goodsName) {
                return $query->where('name', 'like', "%{$goodsName}%");
            })
            ->orderBy('name')
            ->get();

        return $dataList;
    }

    // 组外商品
    public static function outGroupGoods(PriceGroup $model, $goodsName = null)
    {
        $inGroupGoodsIds = self::inGroupGoods($model)->pluck('id');
        $dataList = $model->goods_model::orderBy('name')
            ->when($inGroupGoodsIds, function ($query) use ($inGroupGoodsIds) {
                return $query->whereNotIn('id', $inGroupGoodsIds);
            })
            ->when($goodsName, function ($query) use ($goodsName) {
                return $query->where('name', 'like', "%{$goodsName}%");
            })
            ->get();
        return $dataList;
    }

    // 添加商品
    public static function addGoods($id, $goodsIds)
    {
        $model = self::find($id);

        // 先验证商品是否没有组
        $alreadyHasGroupGoodsIds = PriceGroupGoods::where('price_group_id', $id)->whereIn('goods_id', $goodsIds)->pluck('goods_id');
        if ($alreadyHasGroupGoodsIds->isNotEmpty()) {
            $hasIds = $alreadyHasGroupGoodsIds->implode(',');
            throw new CustomException("{$hasIds} 已添加过！");
        }

        // 写入组商品
        $datetime = date('Y-m-d H:i:s');
        foreach ($goodsIds as $goodsId) {
            $insertData[] = [
                'price_group_id' => $model->id,
                'goods_id'       => $goodsId,
                'created_at'     => $datetime,
                'updated_at'     => $datetime,
            ];
        }

        PriceGroupGoods::insert($insertData);
        try {
        } catch (QueryException $e) {
            throw new CustomException('数据写入失败');
        }

        return true;
    }

    // 获取售价
    public static function priceGroupGoods($priceGroupGoodsId)
    {
        $priceGroupGoods = PriceGroupGoods::find($priceGroupGoodsId);
        return $priceGroupGoods;
    }

    // 变价
    public static function updatePrice($priceGroupGoodsId, $costPrice, $salesPrice)
    {
        $priceGroupGoods = PriceGroupGoods::find($priceGroupGoodsId);
        $priceGroupGoods->cost_price = $costPrice;
        $priceGroupGoods->sales_price = $salesPrice;
        $priceGroupGoods->save();
        return true;
    }

    // 查找用户所在组
    public static function getGroupUserByUserId($goodsModel, $userId)
    {
        $priceGroupUser = PriceGroupUser::with('priceGroup')
            ->where('goods_model', $goodsModel)
            ->where('user_id', $userId)
            ->first();

        return $priceGroupUser;
    }
}
