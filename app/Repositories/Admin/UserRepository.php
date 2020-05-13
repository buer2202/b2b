<?php
namespace App\Repositories\Admin;

use App\Models\User;
use App\Exceptions\CustomException;
use Cache;

class UserRepository
{
	public static function getList($id, $email, $name, $remark)
	{
		$dataList = User::orderBy('id', 'desc')
            ->when($id, function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->when($email, function ($query) use ($email) {
                return $query->where('email', $email);
            })
            ->when($name, function ($query) use ($name) {
                return $query->where('name', $name);
            })
            ->when($remark, function ($query) use ($remark) {
                return $query->where('remark', 'like', "%{$remark}%");
            })
            ->paginate(20);

		return $dataList;
	}

	public static function find($id)
    {
        $data = User::find($id);
        if (empty($data)) {
            throw new CustomException('数据不存在');
        }

        return $data;
    }

    // 设置状态
    public static function setStatus($userId, $status)
    {
        $model = self::find($userId);
        $model->status = $status;
        if (!$model->save()) {
            throw new CustomException('设置失败');
        }

        return true;
    }

    // 设置备注
    public static function setRemark($userId, $remark)
    {
        $model = self::find($userId);
        $model->remark = $remark ?: '';
        if (!$model->save()) {
            throw new CustomException('设置失败');
        }

        return true;
    }
}
