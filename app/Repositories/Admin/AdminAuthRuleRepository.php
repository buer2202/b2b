<?php
namespace App\Repositories\Admin;

use App\Models\AdminAuthRule;
use App\Exceptions\CustomException;
use Illuminate\Database\QueryException;

class AdminAuthRuleRepository
{
    public static function getList($title, $groupName)
    {
        $dataList = AdminAuthRule::orderBy('sortord')
            ->when($title, function ($query) use ($title) {
                return $query->where('title', 'like', "%{$title}%");
            })
            ->when($groupName, function ($query) use ($groupName) {
                return $query->where('group_name', 'like', "%{$groupName}%");
            })
            ->paginate(20);

        return $dataList;
    }

    public static function getGroup()
    {
        $dataList = AdminAuthRule::where('status', 1)->orderBy('sortord')->get();

        $group = [];
        foreach ($dataList as $data) {
            $group[$data['group']]['group_name'] = $data['group_name'];
            $group[$data['group']]['detail'][] = [
                'id'         =>  $data['id'],
                'name'       =>  $data['name'],
                'title'      =>  $data['title'],
                'menu_show'  =>  $data['menu_show'],
                'menu_click' =>  $data['menu_click'],
            ];
        }

        return $group;
    }

    public static function store($name, $title, $group, $groupName, $status, $menuShow, $menuClick, $sortord)
    {
        $model = new AdminAuthRule;
        $model->name       = $name;
        $model->title      = $title;
        $model->group      = $group;
        $model->group_name = $groupName;
        $model->status     = $status;
        $model->menu_show  = $menuShow;
        $model->menu_click = $menuClick;
        $model->sortord    = $sortord;

        try {
            $model->save();
        } catch (QueryException $e) {
            throw new CustomException('权限名已存在');
        }

        return true;
    }

    public static function find($id)
    {
        $data = AdminAuthRule::find($id);
        if (empty($data)) {
            throw new CustomException('数据不存在');
        }

        return $data;
    }

    public static function update($id, $name, $title, $group, $groupName, $status, $menuShow, $menuClick, $sortord)
    {
        $model = self::find($id);
        $model->name       = $name;
        $model->title      = $title;
        $model->group      = $group;
        $model->group_name = $groupName;
        $model->status     = $status;
        $model->menu_show  = $menuShow;
        $model->menu_click = $menuClick;
        $model->sortord    = $sortord;

        try {
            $model->save();
        } catch (QueryException $e) {
            throw new CustomException('权限名已存在');
        }

        // 清除缓存
        AdminRepository::cleanUserCache();

        return true;
    }
}
