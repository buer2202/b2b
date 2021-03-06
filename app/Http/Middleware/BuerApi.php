<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use App\Models\User;
use App\Services\Aes256cbc;
use Auth;
use Closure;

class BuerApi
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
        my_log('buer-api', ['request_url'  => $request->url()]);
        my_log('buer-api', ['request_body' => $request->getContent()]);
        my_log('buer-api', ['request_data' => $request->all()]);

        try {
            if (!$request->filled('secret_id')) {
                throw new CustomException('参数错误: secret_id');
            }
            if (!$request->filled('data')) {
                throw new CustomException('参数错误: data');
            }

            // 查找用户
            $user = $this->getUser($request->secret_id);
            // 提取参数
            $this->getParams($request, $user);
            // 用户登陆
            Auth::login($user);
        } catch (CustomException $e) {
            my_log('buer-api', ['error' => $e->getMessage()]);
            return response()->buerApi(0, $e->getMessage());
        }

        return $next($request);
    }

    private function getUser($secretId)
    {
        // 查找用户
        $user = User::where('secret_id', $secretId)->first();
        if (!$user) {
            throw new CustomException('ID不存在');
        }
        if ($user->status != 1) {
            throw new CustomException('您的账号已禁用');
        }
        return $user;
    }

    public function getParams($request, $user)
    {
        $params = (new Aes256cbc($user->secret_key))->decrypt($request->data, true, true);
        my_log('buer-api', ['decrypted_data' => $params]);

        // 替换$request参数
        $request->replace($params);

        // 验证时间
        if (!$request->filled('timestamp')) {
            throw new CustomException('参数有误: timestamp');
        }

        if (abs(time() - (int)$request->timestamp) > 300) {
            throw new CustomException('请求过期');
        }
    }
}
