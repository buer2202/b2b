<?php

namespace App\Http\Controllers\Admin\User;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\HomeAuthRoleRepository;
use App\Repositories\Admin\HomeAuthRuleRepository;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roleList = HomeAuthRoleRepository::getList();
        $ruleGroup = HomeAuthRuleRepository::getGroup();
        return view('admin.user.role.index', compact('roleList', 'ruleGroup'));
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
            HomeAuthRoleRepository::store($request->name);
        }
        catch (CustomException $e) {
            return back()->with(['alert' => $e->getMessage()]);
        }

        return redirect()->route('admin.user.role.index');
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
            $role = HomeAuthRoleRepository::find($id);
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
            $model = HomeAuthRoleRepository::update($id, $request->name, $request->status);
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
            HomeAuthRoleRepository::updateRule($id, $request->rule_ids);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
