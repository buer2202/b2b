<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;
use App\Exceptions\CustomException;
use Route;
use Cache;

class Admin extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 更新密码
    public function updatePassword($originPassword, $password)
    {
        if (!Hash::check($originPassword, $this->password)) {
            throw new CustomException('原始密码不正确');
        }

        $this->password = Hash::make($password);
        if (!$this->save()) {
            throw new CustomException('密码修改失败');
        }

        return true;
    }

    // 用户角色
    public function roles()
    {
        return $this->belongsToMany(AdminAuthRole::class, 'admin_auth_user_roles');
    }

    // 用户表角色关系
    public function userRoles()
    {
        return $this->hasMany(AdminAuthUserRole::class);
    }

    // 是否是administrator
    public function isAdministrator()
    {
        // 管理员id为1的不受角色限制
        if ($this->id == 1) {
            return true;
        }

        // 组id为1默认是administrator
        return $this->userRoles()->where('admin_auth_role_id', 1)->count() ? true : false;
    }

    // 权限验证
    public function checkRule()
    {
        $currentRouteName = Route::currentRouteName();

        // 例外的路由
        if (in_array($currentRouteName, config('rbac.admin_except'))) {
            return true;
        }

        // 只有administrator才能访问system组权限
        if (explode('.', $currentRouteName)[1] == 'system') {
            if ($this->isAdministrator()) {
                return true;
            } else {
                return false;
            }
        }

        // 验证权限并缓存结果
        $hasRule = Cache::tags('admin:admin:rule')->remember($this->id . $currentRouteName, 1440, function () use ($currentRouteName) {
            // 获取用户角色
            $roles = $this->roles()->where('admin_auth_roles.status', 1)->pluck('admin_auth_roles.id');

            // 验证角色权限
            $hasRule = AdminAuthRule::join('admin_auth_role_rules', 'admin_auth_role_rules.admin_auth_rule_id', '=', 'admin_auth_rules.id')
                ->whereIn('admin_auth_role_rules.admin_auth_role_id', $roles)
                ->where('admin_auth_rules.status', 1)
                ->where('admin_auth_rules.name', $currentRouteName)
                ->first();

            return $hasRule ? true : false;
        });

        return $hasRule;
    }

    // 获取菜单
    public function getMenus()
    {
        $menus = Cache::tags('admin:admin:menu')->remember($this->id, 1440, function () {
            // 获取用户角色
            $roles = $this->roles()->where('admin_auth_roles.status', 1)->pluck('admin_auth_roles.id');

            // 获取角色权限
            $rules = AdminAuthRule::join('admin_auth_role_rules', 'admin_auth_role_rules.admin_auth_rule_id', '=', 'admin_auth_rules.id')
                ->distinct()
                ->whereIn('admin_auth_role_rules.admin_auth_role_id', $roles)
                ->where('admin_auth_rules.status', 1)
                ->where('admin_auth_rules.menu_show', 1)
                ->where('admin_auth_rules.menu_click', 1)
                ->select('admin_auth_rules.*')
                ->orderBy('admin_auth_rules.sortord')
                ->get();

            // 构造容易输出的数据结构
            $menus = [];
            foreach ($rules as $value) {
                $menus[$value['group']]['group_name'] = $value['group_name'];
                $menus[$value['group']]['items'][] = [
                    'title' => $value['title'],
                    'name'  => $value['name'],
                ];
            }

            return $menus;
        });

        return $menus;
    }
}
