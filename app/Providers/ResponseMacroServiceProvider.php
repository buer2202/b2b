<?php

namespace App\Providers;

use App\Services\Aes256cbc;
use Auth;
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

        // 平台对公API响应
        Response::macro('api', function ($status, $message = 'success', $data = '') {
            $respArr = ['status' => $status, 'message' => $message, 'data' => $data];
            my_log('api-resp', $respArr);

            if ($data && Auth::check()) {
                $respArr['data'] = (new Aes256cbc(Auth::user()->secret_key))->encrypt(json_encode($data, JSON_UNESCAPED_UNICODE));
            }

            return response()->json($respArr, 200, ["Content-type" => "application/json;charset=utf-8"], JSON_UNESCAPED_UNICODE);
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
