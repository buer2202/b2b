<?php

namespace App\Repositories\Commands;

use App\Models\Notification;
use GuzzleHttp\Client;

class NotificationRepository
{
    // 创建任务
    public static function create($requestUrl, $requestData, $dataFormat = 'json', $requestNumber = 10)
    {
        $model = new Notification;
        $model->request_url = $requestUrl;
        $model->request_data = json_encode($requestData);
        $model->data_format = $dataFormat;
        $model->request_number = $requestNumber;
        $model->status = 1;
        $model->save();

        return true;
    }

    // 发送全部
    public static function sendAll()
    {
        $tasks = Notification::where('status', 1)->where('request_number', '>', 0)->get();
        if ($tasks->isEmpty()) {
            return true;
        }

        foreach ($tasks as $task) {
            self::sendOne($task);
        }

        return true;
    }

    // 发送一条
    public static function sendOne(Notification $model)
    {
        // 更新任务
        $model->request_times++; // 已请求次数
        if ($model->request_times >= $model->request_number) {
            $model->status = 2;
        }
        $model->save(); // 先保存，后面失败了不管

        $data = json_decode($model->request_data, true);
        my_log('notification', ['url' => $model->request_url, 'data' => $data]);

        switch ($model->data_format) {
            case 'json':
                $options['timeout'] = 3; // 超时时间
                $options['json'] = $data;
                break;
            default:
                $options = $data;
                break;
        }

        try {
            $res = (new Client)->post($model->request_url, $options);
            $contents = $res->getBody()->getContents();
        } catch (\Exception $e) {
            $model->last_response = $e->getMessage();
            $model->save();
            my_log('notification', ['contents' => $model->last_response]);
            return true;
        }

        if ($res->getStatusCode() == 200) {
            $model->status = 2;
        }
        $model->last_response = $contents;
        $model->last_http_code = $res->getStatusCode();
        $model->save();
        my_log('notification', ['http_code' => $model->last_http_code, 'contents' => $model->last_response]);

        return true;
    }
}
