<?php
namespace App\Repositories\Admin;

use App\Models\Admin;
use App\Exceptions\CustomException;
use Auth;
use Cache;

class AdminRepository
{
    public static function getList($name)
    {
        $dataList = Admin::withTrashed()
            ->when($name, function ($query) use ($name) {
                return $query->where('name', $name);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        return $dataList;
    }

    public static function find($id)
    {
        $data = Admin::withTrashed()->find($id);
        if (empty($data)) {
            throw new CustomException('数据不存在');
        }

        return $data;
    }

    public static function store($name, $password)
    {
        $model = new Admin;
        $model->name     = $name;
        $model->password = $password;

        if (!$model->save()) {
            throw new CustomException('数据创建失败');
        }

        return true;
    }

    public static function update($id, $password)
    {
        $model = self::find($id);
        $model->password = $password;

        if (!$model->save()) {
            throw new CustomException('数据更新失败');
        }

        return true;
    }

    // 禁用（软删除）
    public static function destroy($id)
    {
        if (Auth::user()->id == $id) {
            throw new CustomException('不能禁用当前用户');
        }

        $model = self::find($id);

        if (!$model->delete()) {
            throw new CustomException('数据更新失败');
        }

        return true;
    }

    // 启用（恢复软删除）
    public static function restore($id)
    {
        $model = self::find($id);

        if (!$model->restore()) {
            throw new CustomException('数据更新失败');
        }

        return true;
    }

    public static function cleanUserCache($tags = [])
    {
        $tags = $tags ?: ['admin:admin:rule', 'admin:admin:menu'];

        Cache::tags($tags)->flush();

        return true;
    }

    // 更新用户角色
    public static function updateRoles($id, $roleIds)
    {
        $model = self::find($id);

        // 更新权限
        $model->roles()->sync($roleIds);

        // 清除缓存
        self::cleanUserCache();

        return true;
    }
}
