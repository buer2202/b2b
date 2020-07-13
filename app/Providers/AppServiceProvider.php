<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 手动配置迁移命令migrate生成的默认字符串长度
        // \Schema::defaultStringLength(191);

        // 常规状态
        $GLOBALS['status'] = [0 => '禁用', 1 => '正常'];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
