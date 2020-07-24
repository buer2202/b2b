<?php

namespace App\Http\Controllers\Admin\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\Admin\AdminAuthRoleRepository;
use App\Exceptions\CustomException;

// 管理员管理
class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataList = AdminRepository::getList($request->name);
        $roles = AdminAuthRoleRepository::getList(1);

        return view('admin.system.administrator.index', compact('dataList', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'bail|required|string',
            'password' => 'bail|required|string|min:6|max:20',
        ]);

        try {
            AdminRepository::store($request->name, bcrypt($request->password));
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['password' => 'bail|required|string|min:6|max:20']);

        try {
            AdminRepository::update($id, bcrypt($request->password));
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            AdminRepository::destroy($id);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 启用
    public function restore($id)
    {
        try {
            AdminRepository::restore($id);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    // 角色
    public function roles($id)
    {
        try {
            $admin = AdminRepository::find($id);
            $roles = $admin->roles->pluck('id');
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $roles);
    }

    // 更新角色
    public function updateRoles(Request $request, $id)
    {
        try {
            AdminRepository::updateRoles($id, $request->role_ids);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
