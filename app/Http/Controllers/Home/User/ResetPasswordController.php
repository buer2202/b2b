<?php

namespace App\Http\Controllers\Home\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\CustomException;
use App\Repositories\Home\UserRepository;

class ResetPasswordController extends Controller
{
    public function index(Request $request)
    {
        return view('home.user.reset-password.index');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'origin_password' => 'bail|required|string',
            'password' => 'bail|required|string|min:6|max:20|confirmed',
        ]);

        try {
            UserRepository::updatePassword($request->origin_password, $request->password);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
