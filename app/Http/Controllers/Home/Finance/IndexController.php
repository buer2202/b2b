<?php

namespace App\Http\Controllers\Home\Finance;

use App\Http\Controllers\Controller;
use Auth;

class IndexController extends Controller
{
    public function index()
    {
        $asset = Auth::user()->userAsset;
        return view('home.finance.index.index', compact('asset'));
    }
}
