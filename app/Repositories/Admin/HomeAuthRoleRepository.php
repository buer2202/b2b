<?php
namespace App\Repositories\Admin;

use App\Models\HomeAuthRole;
use App\Exceptions\CustomException;

class HomeAuthRoleRepository
{
    public static function getList($status = null)
    {
        $dataList = HomeAuthRole::orderBy('id', 'desc')
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->get();

        return $dataList;
    }

    public static function store($name)
    {
        $model = new HomeAuthRole;
        $model->name   = $name;
        $model->status = 1;

        if (!$model->save()) {
            throw new CustomException('数据创建失败');
        }

        return true;
    }

    public static function find($id)
    {
        $data = HomeAuthRole::find($id);
        if (empty($data)) {
            throw new CustomException('数据不存在');
        }

        return $data;
    }

    public static function update($id, $name, $status)
    {
        $model = self::find($id);

        if ($name) {
            $model->name = $name;
        } else {
            $model->status = $status;
        }

        if (!$model->save()) {
            throw new CustomException('数据更新失败');
        }

        // 清除缓存
        UserRepository::cleanUserCache();

        return $model;
    }

    // 更新角色权限
    public static function updateRule($id, $ruleIds)
    {
        $model = self::find($id);

        // 更新权限
        $model->rules()->sync($ruleIds);

        // 清除缓存
        UserRepository::cleanUserCache();

        return true;
    }
}
