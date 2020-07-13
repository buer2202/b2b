<?php

namespace App\Jobs\Exports;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\RemoveFile;
use Buer\Asset\Models\PlatformAmountFlow;
use Carbon\Carbon;
use GatewayClient\Gateway;

class PlatformAmountFlowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 10;
    public $timeout = 600;

    protected $gatewayGroupId;
    protected $userId;
    protected $tradeNo;
    protected $tradeType;
    protected $tradeSubtype;
    protected $startTime;
    protected $endTime;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($gatewayGroupId, $userId, $tradeNo, $tradeType, $tradeSubtype, $startTime, $endTime)
    {
        $this->gatewayGroupId = $gatewayGroupId;
        $this->userId         = $userId;
        $this->tradeNo        = $tradeNo;
        $this->tradeType      = $tradeType;
        $this->tradeSubtype   = $tradeSubtype;
        $this->startTime      = $startTime;
        $this->endTime        = $endTime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $assetTradeType = config('asset.trade_type');

        $dir = base_path('public/exports');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $fileName = 'platform_amount_flow_at_' . date('Y-m-d-H:i:s') . '.csv';
        $filePath = $dir . "/{$fileName}";
        $file = fopen($filePath, 'w');

        fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($file, [
            '流水号',
            '用户',
            '管理员',
            '类型',
            '子类型',
            '相关单号',
            '金额',
            '备注',
            '平台资金',
            '平台托管',
            '用户余额',
            '用户冻结',
            '累计用户加款',
            '累计用户提现',
            '累计用户消费',
            '累计退款给用户',
            '累计用户成交次数',
            '累计用户成交金额',
            '时间',
        ]);

        PlatformAmountFlow::orderBy('id', 'desc')
            ->when($this->userId, function ($query) {
                return $query->where('user_id', $this->userId);
            })
            ->when($this->tradeNo, function ($query) {
                return $query->where('trade_no', $this->tradeNo);
            })
            ->when($this->tradeType, function ($query) {
                return $query->where('trade_type', $this->tradeType);
            })
            ->when($this->tradeSubtype, function ($query) {
                return $query->where('trade_subtype', $this->tradeSubtype);
            })
            ->when($this->startTime, function ($query) {
                return $query->where('created_at', '>=', $this->startTime);
            })
            ->when($this->endTime, function ($query) {
                return $query->where('created_at', '<=', Carbon::parse($this->endTime)->endOfDay());
            })

            ->chunk(1000, function ($dataList) use ($file, $assetTradeType) {
                static $count = 0;

                foreach ($dataList as $data) {
                    fputcsv($file, [
                        $data->id,
                        $data->user_id,
                        $data->admin_user_id,
                        $assetTradeType['platform'][$data->trade_type] ?? $data->trade_type,
                        $assetTradeType['user_sub'][$data->trade_subtype] ?? $data->trade_subtype,
                        "'" . $data->trade_no,
                        $data->fee,
                        $data->remark,
                        $data->amount,
                        $data->managed,
                        $data->balance,
                        $data->frozen,
                        $data->total_recharge,
                        $data->total_withdraw,
                        $data->total_consume,
                        $data->total_refund,
                        $data->total_trade_quantity,
                        $data->total_trade_amount,
                        $data->created_at,
                    ]);
                }

                $count += $dataList->count();
                Gateway::sendToGroup($this->gatewayGroupId, json_encode(['type' => 'exporting_platform_amount_flow', 'message' => $count]));
            }
        );

        // 发送完成消息
        Gateway::sendToGroup($this->gatewayGroupId, json_encode(['type' => 'complete_platform_amount_flow', 'message' => "/exports/{$fileName}"]));

        // 10分钟后删除文件
        RemoveFile::dispatch($filePath)->delay(Carbon::now()->addMinutes(10));
    }
}
