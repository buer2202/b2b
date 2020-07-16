<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\HomeAuthRuleRepository;
use App\Exceptions\CustomException;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataList = HomeAuthRuleRepository::getList($request->title, $request->group_name);
        return view('admin.user.rule.index', compact('dataList', 'config'));
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
            'name'       => 'bail|required|string',
            'title'      => 'bail|required|string',
            'group'      => 'bail|required|string',
            'group_name' => 'bail|required|string',
            'status'     => 'bail|required|in:0,1',
            'menu_show'  => 'bail|required|in:0,1',
            'menu_click' => 'bail|required|in:0,1',
            'sortord'    => 'bail|required|integer',
        ]);

        try {
            HomeAuthRuleRepository::store(
                $request->name,
                $request->title,
                $request->group,
                $request->group_name,
                $request->status,
                $request->menu_show,
                $request->menu_click,
                $request->sortord
            );
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $channel = HomeAuthRuleRepository::find($id);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1, 'success', $channel);
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
        $this->validate($request, [
            'name'       => 'bail|required|string',
            'title'      => 'bail|required|string',
            'group'      => 'bail|required|string',
            'group_name' => 'bail|required|string',
            'status'     => 'bail|required|in:0,1',
            'menu_show'  => 'bail|required|in:0,1',
            'menu_click' => 'bail|required|in:0,1',
            'sortord'    => 'bail|required|integer',
        ]);

        try {
            HomeAuthRuleRepository::update(
                $id,
                $request->name,
                $request->title,
                $request->group,
                $request->group_name,
                $request->status,
                $request->menu_show,
                $request->menu_click,
                $request->sortord
            );
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
