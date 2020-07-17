<?php

namespace App\Exports\Admin;

use Buer\Asset\Models\PlatformAssetDaily as ModelsPlatformAssetDaily;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class PlatformAssetDaily implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return  ModelsPlatformAssetDaily::orderBy('date', 'desc')
            ->when($this->request->start_time, function ($query) {
                return $query->where('date', '>=', $this->request->start_time);
            })
            ->when($this->request->end_time, function ($query) {
                return $query->where('date', '<=', $this->request->end_time);
            });
    }

    public function headings(): array
    {
        return [
            '日期',
            '平台资金',
            '平台托管',
            '用户余额',
            '用户冻结',
            '当日用户加款',
            '累计用户加款',
            '当日用户提现',
            '累计用户提现',
            '当日用户消费',
            '累计用户消费',
            '当日退款给用户',
            '累计退款给用户',
            '当日用户成交次数',
            '累计用户成交次数',
            '当日用户成交',
            '累计用户成交',
        ];
    }

    /**
    * @var Invoice $invoice
    */
    public function map($data): array
    {
        return [
            'date'                 => $data['date'],
            'amount'               => $data['amount'],
            'managed'              => $data['managed'],
            'balance'              => $data['balance'],
            'frozen'               => $data['frozen'],
            'recharge'             => $data['recharge'],
            'total_recharge'       => $data['total_recharge'],
            'withdraw'             => $data['withdraw'],
            'total_withdraw'       => $data['total_withdraw'],
            'consume'              => $data['consume'],
            'total_consume'        => $data['total_consume'],
            'refund'               => $data['refund'],
            'total_refund'         => $data['total_refund'],
            'trade_quantity'       => $data['trade_quantity'] ? : '0',
            'total_trade_quantity' => $data['total_trade_quantity'] ?: '0',
            'trade_amount'         => $data['trade_amount'],
            'total_trade_amount'   => $data['total_trade_amount'],
        ];
    }
}
