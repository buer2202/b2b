<?php

namespace App\Http\Controllers\Admin\User;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\UserRepository;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $dataList = UserRepository::getList($request->user_id, $request->email, $request->name, $request->remark);
        return view('admin.user.index.index', compact('dataList'));
    }

    // 获取密钥
    public function info($userId)
    {
        $user = UserRepository::find($userId);

        $html = '';
        if ($user->type == 2) {
            $html .= "<p>企业名：{$user->company}</p>";
            $html .= "<p>营业执照号：{$user->license}</p>";
        }

        $html .= "<p>真实姓名：{$user->real_name}</p>";
        $html .= "<p>身份证号：{$user->id_number}</p>";
        $html .= "<p>手机号码：{$user->phone}</p>";
        $html .= "<p>联系qq：{$user->qq}</p>";
        $html .= "<p>API密钥：</p><p class=\"text-warning\">{$user->api_secret}</p>";

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
