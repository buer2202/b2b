<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        my_config(['a' => 'bbb']);
        dump(my_config('a', 'b'));
    }
}
