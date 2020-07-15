<?php

namespace App\Http\Controllers\Home\WebSocket;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GatewayClient\Gateway;

class GatewayWorkerController extends Controller
{
    public function bind(Request $request)
    {
        if (!$request->filled('client_id')) {
            return response()->ajax(0, '参数错误');
        }

        // 加入已用户id命名的群组
        $groupId = gateway_group_id('home');
        Gateway::joinGroup($request->client_id, $groupId);
        Gateway::sendToGroup($groupId, '{"home":"bind test"}');

        return response()->ajax(1);
    }
}
