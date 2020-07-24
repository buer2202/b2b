<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Exceptions\CustomException;

class PasswordController extends Controller
{
    public function index()
    {
        return view('admin.password.index');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'origin_password' => 'bail|required|string',
            'password' => 'bail|required|string|min:6|max:20|confirmed',
        ]);

        try {
            Auth::user()->updatePassword($request->origin_password, $request->password);
        }
        catch (CustomException $e) {
            return response()->ajax(0, $e->getMessage());
        }

        return response()->ajax(1);
    }
}
