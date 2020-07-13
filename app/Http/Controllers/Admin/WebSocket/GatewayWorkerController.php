<?php

namespace App\Http\Controllers\Admin\WebSocket;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GatewayWorker\Lib\Gateway;

class GatewayWorkerController extends Controller
{
    public function bind(Request $request)
    {
        if (!$request->filled('client_id')) {
            return response()->ajax(0, '参数错误');
        }

        // 加入已用户id命名的群组
        $groupId = gateway_group_id('admin');
        Gateway::joinGroup($request->client_id, $groupId);
        Gateway::sendToGroup($groupId, '{"admin":"bind test"}');

        return response()->ajax(1);
    }
}
