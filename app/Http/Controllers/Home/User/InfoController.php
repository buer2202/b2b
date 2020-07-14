<?php

namespace App\Http\Controllers\Home\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Home\UserRepository;

// 用户信息
class InfoController extends Controller
{
    public function index()
    {
        return view('home.user.info.index');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'phone' => 'bail|required|string|max:20',
        ]);

        UserRepository::updateInfo($request->phone);
        return response()->ajax(1);
    }
}
