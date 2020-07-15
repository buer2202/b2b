<?php

namespace App\Http\Controllers\Home\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class IndexController extends Controller
{
    public function index()
    {
        $asset = Auth::user()->asset;
        return view('home.finance.index.index', compact('asset'));
    }
}
