<?php

namespace App\Http\Controllers\Admin\System;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminAuthRoleRepository;
use App\Repositories\Admin\AdminAuthRuleRepository;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roleList = AdminAuthRoleRepository::getList();
        $ruleGroup = AdminAuthRuleRepository::getGroup();
        $type = config('user.role_type');

        return view('admin.system.role.index', compact('roleList', 'ruleGroup', 'type'));
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
            'name' => 'bail|required|string|max:50',
        ]);

        try {
            AdminAuthRoleRepository::store($request->name);
        }
        catch (CustomException $e) {
            return back()->with(['alert' => $e->getMessage()]);
        }

        return back();
    }

    /**
     * 获取角色权限
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $role = AdminAuthRoleRepository::find($id);
            $rules = $role->rules;
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $rules);
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
        switch ($request->method()) {
            case 'PUT': // 修改
                $this->validate($request, [
                    'name' => 'bail|required|string',
                ]);

                break;

            case 'PATCH': // 禁用/启用
                $this->validate($request, [
                    'status' => 'bail|required|in:0,1',
                ]);
                break;
        }

        try {
            $model = AdminAuthRoleRepository::update($id, $request->name, $request->status);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', [
            'name'   => $model->name,
            'status' => $model->status,
        ]);
    }

    // 更新角色权限
    public function updateRules(Request $request, $id)
    {
        try {
            AdminAuthRoleRepository::updateRule($id, $request->rule_ids);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
