<?php

namespace App\Http\Controllers\Home\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        return view('home.user.index.index');
    }
}
