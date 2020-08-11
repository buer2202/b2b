<?php

namespace App\Http\Controllers\Admin\User;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\HomeAuthRoleRepository;
use App\Repositories\Admin\UserRepository;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $dataList = UserRepository::getList($request->user_id, $request->email, $request->name, $request->remark);
        $roles = HomeAuthRoleRepository::getList(1);
        $config = config('user');
        return view('admin.user.index.index', compact('dataList', 'roles', 'config'));
    }

    // 获取密钥
    public function apiSecret($userId)
    {
        $user = UserRepository::find($userId);
        $html = "<b>secret_id：</b><p>{$user->secret_id}</p>"
              . "<b>secret_key：</b><p>{$user->secret_key}</p>";

        return response()->ajax(1, 'success', $html);
    }

    public function status(Request $request, $id)
    {
        $this->validate($request, ['status' => 'required|in:0,1']);

        try {
            UserRepository::setStatus($id, $request->status);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    public function remark(Request $request, $id)
    {
        try {
            UserRepository::setRemark($id, $request->remark);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 用户角色
    public function roles($id)
    {
        try {
            $user = UserRepository::find($id);
            $roles = $user->roles->pluck('id');
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $roles);
    }

    // 更新角色
    public function updateRoles(Request $request, $id)
    {
        try {
            UserRepository::updateRoles($id, $request->role_ids);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
