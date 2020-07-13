<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeAuthRole extends Model
{
    // 角色权限
    public function rules()
    {
        return $this->belongsToMany(HomeAuthRule::class, 'home_auth_role_rules');
    }
}
