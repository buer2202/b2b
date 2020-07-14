<?php

namespace App\Repositories\Home;

use App\Exceptions\CustomException;
use Auth;
use Hash;

class UserRepository
{
    public static function updateInfo($phone)
    {
        Auth::user()->phone = $phone;
        Auth::user()->save();
        return true;
    }

    // 更新密码
    public static function updatePassword($originPassword, $password)
    {
        if (!Hash::check($originPassword, Auth::user()->password)) {
            throw new CustomException('原始密码不正确');
        }

        Auth::user()->password = Hash::make($password);
        Auth::user()->save();
        return true;
    }
}
