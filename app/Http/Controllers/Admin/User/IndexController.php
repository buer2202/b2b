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
}
