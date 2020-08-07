<?php

namespace App\Http\Controllers\Admin\System;

use App\Exceptions\CustomException;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    public function index()
    {
        $dataList = Config::paginate(20);
        return view('admin.system.config.index', compact('dataList'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'item'        => 'bail|required|string',
            'value'       => 'bail|required|string',
            'description' => 'bail|required|string',
        ]);

        try {
            my_config([$request->item => $request->value], $request->description);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }

    public function show(Request $request)
    {
        $this->validate($request, ['item' => 'bail|required|string']);

        try {
            $data = my_config($request->item);
        } catch (CustomException $e) {
            return response()->ajax(0, '配置项不存在');
        }

        return response()->ajax(1, 'success', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'item'  => 'bail|required|string',
            'value' => 'bail|required|string',
        ]);

        try {
            my_config([$request->item => $request->value]);
        } catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
