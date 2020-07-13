<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Route;
use View;

class HomeRBAC
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 权限验证
        if (!Auth::user()->checkRule()) {
            if ($request->ajax()) {
                return response()->ajax(0, '没有权限');
            } else {
                abort(403);
            }
        }

        // 构造菜单
        $menus = Auth::user()->getMenus();
        $currentRouteName = Route::currentRouteName();
        $route = explode('.', $currentRouteName);

        // 构造菜单视图
        View::share('currentRouteName', $currentRouteName);
        View::share('route', $route);
        View::share('menus', $menus);

        // 构造面包屑视图（不存在的页面不会显示菜单，所以没有else）
        if (isset($menus[$route[1]])) {
            View::share('breadcrumbMiddlename', $menus[$route[1]]['group_name']);

            $subMenuPluck = collect($menus[$route[1]]['items'])->pluck('title', 'name');
            if (isset($subMenuPluck[$currentRouteName])) {
                View::share('breadcrumbLastname', $subMenuPluck[$currentRouteName]);
            }
        }

        return $next($request);
    }
}
