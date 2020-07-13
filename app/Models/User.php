<?php

namespace App\Models;

use App\Exceptions\CustomException;
use Buer\Asset\Models\UserAsset;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cache;
use Hash;
use Route;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 创建资产
    public function createAsset()
    {
        $this->asset()->save(new UserAsset);
    }

    // 获取资产
    public function userAsset()
    {
        return $this->hasOne(UserAsset::class);
    }

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

    // 权限验证
    public function checkRule($routeName = null)
    {
        $currentRouteName = $routeName ?: Route::currentRouteName();

        // 例外的路由
        if (in_array($currentRouteName, config('user.rbac_except'))) {
            return true;
        }

        // 验证权限并缓存结果
        $hasRule = Cache::tags('home:user:rule')->remember($this->id . $currentRouteName, 1440, function () use ($currentRouteName) {
            // 获取用户角色
            $roles = $this->roles()->where('home_auth_roles.status', 1)->pluck('home_auth_roles.id');

            // 验证角色权限
            $hasRule = HomeAuthRule::join('home_auth_role_rules', 'home_auth_role_rules.home_auth_rule_id', '=', 'home_auth_rules.id')
                ->whereIn('home_auth_role_rules.home_auth_role_id', $roles)
                ->where('home_auth_rules.status', 1)
                ->where('home_auth_rules.name', $currentRouteName)
                ->first();

            return $hasRule ? true : false;
        });

        return $hasRule;
    }

    // 获取菜单
    public function getMenus()
    {
        $menus = Cache::tags('home:user:menu')->remember($this->id, 1440, function () {
            // 获取用户角色
            $roles = $this->roles()->where('home_auth_roles.status', 1)->pluck('home_auth_roles.id');

            // 获取角色权限
            $rules = HomeAuthRule::join('home_auth_role_rules', 'home_auth_role_rules.home_auth_rule_id', '=', 'home_auth_rules.id')
                ->distinct()
                ->whereIn('home_auth_role_rules.home_auth_role_id', $roles)
                ->where('home_auth_rules.status', 1)
                ->where('home_auth_rules.menu_show', 1)
                ->select('home_auth_rules.*')
                ->orderBy('home_auth_rules.sortord')
                ->get();

            // 构造容易输出的数据结构
            $menus = [];
            foreach ($rules as $value) {
                $menus[$value['group']]['group_name'] = $value['group_name'];
                $menus[$value['group']]['items'][] = [
                    'title'      => $value['title'],
                    'name'       => $value['name'],
                    'menu_click' => $value['menu_click'],
                ];
            }

            return $menus;
        });

        return $menus;
    }
}
