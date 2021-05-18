<?php

use App\Exceptions\CustomException;

if (!function_exists('my_log')) {
    /**
     * 自定义日志写入
     * @param $fileName
     * @param array $data
     */
    function my_log($fileName, $data)
    {
        if (!is_string($fileName)) {
            throw new CustomException('文件名必须是字符串');
        }
        $data = ['logGroup' => LARAVEL_START, 'logData' => $data];

        $log = new \Monolog\Logger($fileName);
        $log->pushHandler(new \Monolog\Handler\StreamHandler(storage_path() . '/logs/' . $fileName . '-' . date('Y-m-d') . '.log'));
        $log->addInfo($fileName, $data);

        return true;
    }
}

if (!function_exists('generate_order_no')) {
    /**
     * 生成22位订单号
     * @return string
     */
    function generate_order_no()
    {
        // 获取本月自增变量
        $increments = \Illuminate\Support\Facades\Redis::incr('increments:order:' . date('Ym'));

        // 14位年月日 + 拼2位随机数 + 6位自增id
        $orderNo = date('YmdHis') . rand(10, 99) . str_pad($increments, 6, 0, STR_PAD_LEFT);

        return $orderNo;
    }
}

// 获取websocket的用户分组id
if (!function_exists('gateway_group_id')) {
    /**
     * @param $moduleName 模块名称，如 Home, Admin
     * @param string $identify 身份唯一标识，不传就用user_id
     * @return string
     */
    function gateway_group_id($moduleName, $identify = '')
    {
        if (!$identify) {
            $identify = \Auth::id();
        }
        $groupId = 'platform_' . config('app.platform_id') . '_' . $moduleName . '_' . $identify;

        return $groupId;
    }
}

if (!function_exists('get_float_length')) {
    /**
     * 获取小数位数
     * @return string
     */
    function get_float_length($value)
    {
        // 获取小数位数
        $value = (float)$value; // 去掉小数末尾的0
        $arr = explode('.', $value);

        if (isset($arr[1])) {
            return strlen($arr[1]);
        } else {
            return 0;
        }
    }
}

if (!function_exists('is_weixin')) {
    function is_weixin()
    {
        return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
    }
}

if (!function_exists('my_config')) {
    /**
     * 更新自定义配置
     * @return string
     */
    function my_config($item, $default = null)
    {
        if (!$item) {
            throw new CustomException('配置项名不能为空');
        }

        // 如果item是数组，就存
        if (is_array($item)) {
            if (count($item) != 1) {
                throw new CustomException('一次只能存一组数据');
            }

            $config = \App\Models\Config::firstOrNew(['item' => key($item)]);
            $config->value = trim(current($item)) ?: '';
            $default && $config->description = $default;
            if (!$config->save()) {
                throw new CustomException('写缓存失败');
            }

            // 清除缓存
            \Cache::tags('config')->flush();
            return true;
        } else { // 否则就取
            $value = \Cache::tags('config')->remember($item, 1440, function () use ($item) {
                return \App\Models\Config::where('item', $item)->value('value');
            });

            return is_null($value) ? $default : $value;
        }
    }
}

/**
 * 验证日期字符串
 */
if (!function_exists('checkDatetime')) {
    function checkDateTime(string $dateTimeString, string $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($dateTimeString)) == $dateTimeString;
    }
}

// 表单验证
if (!function_exists('my_validator')) {
    /**
     * 更新自定义配置
     * @return string
     */
    function my_validator(array $item, $throwException = true)
    {
        $validator = validator()->make(request()->all(), $item);
        if ($validator->fails()) {
            if ($throwException) {
                throw new CustomException($validator->errors()->first());
            } else {
                return $validator->errors()->first();
            }
        }
        return null;
    }
}
