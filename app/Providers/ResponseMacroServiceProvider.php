<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // ajax响应宏
        Response::macro('ajax', function ($status, $message = '', $contents= []) {
            return response()->json(['status' => $status, 'message' => $message, 'contents' => $contents], 200, ['Content-type' => 'application/json;charset=utf-8'], JSON_UNESCAPED_UNICODE);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
