<?php

namespace App\Http\Controllers\Admin\PriceSystem;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\PriceGroupRepository;

class PriceGroupUserController extends Controller
{
    // 组内用户管理
    public function inside($groupId, Request $request)
    {
        $group = PriceGroupRepository::find($groupId);
        $dataList = PriceGroupRepository::inGroupUsers($group, $request->search_key);
        $bizGroups = PriceGroupRepository::getByGoodsModel($group->goods_model, $group->id);
        return view('admin.price-system.price-group-user.inside', compact('group', 'dataList', 'bizGroups'));
    }

    // 组外用户管理
    public function outside($groupId, Request $request)
    {
        $group = PriceGroupRepository::find($groupId);
        $dataList = PriceGroupRepository::outGroupUsers($group->goods_model, $request->search_key);
        return view('admin.price-system.price-group-user.outside', compact('group', 'dataList'));
    }

    // 添加用户
    public function add($groupId, Request $request)
    {
        try {
            if (!$request->filled('users')) {
                throw new CustomException('请先勾选用户');
            }

            PriceGroupRepository::addUsers($groupId, $request->users);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 删除用户
    public function delete($groupId, Request $request)
    {
        try {
            if (!$request->filled('users')) {
                throw new CustomException('请先勾选用户');
            }

            PriceGroupRepository::deleteUsers($groupId, $request->users);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 移动用户
    public function move($groupId, Request $request)
    {
        try {
            if (!$request->filled('users')) {
                throw new CustomException('请先勾选用户');
            }

            PriceGroupRepository::moveUsers($groupId, $request->new_group_id, $request->users);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
