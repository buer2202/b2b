<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAuthRole extends Model
{
    // 角色权限
    public function rules()
    {
        return $this->belongsToMany(AdminAuthRule::class, 'admin_auth_role_rules');
    }
}
