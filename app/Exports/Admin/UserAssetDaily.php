<?php

namespace App\Exports\Admin;

use Buer\Asset\Models\UserAssetDaily as ModelsUserAssetDaily;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserAssetDaily implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return ModelsUserAssetDaily::orderBy('id', 'desc')
            ->when($this->request->user_id, function ($query) {
                return $query->where('user_id', $this->request->user_id);
            })
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
            '用户ID',
            '剩余金额',
            '冻结金额',
            '当日往平台加款',
            '累计往平台加款',
            '当日从平台提现',
            '累计从平台提现',
            '当日在平台消费',
            '累计在平台消费',
            '当日从平台收到退款',
            '累计从平台收到退款',
            '当日交易支出',
            '累计交易支出',
            '当日交易收入',
            '累计交易收入',
        ];
    }

    /**
    * @var Invoice $invoice
    */
    public function map($data): array
    {
        return [
            'date' => $data['date'],
            'user_id' => $data['user_id'],
            'balance' => $data['balance'],
            'frozen' => $data['frozen'],
            'recharge' => $data['recharge'],
            'total_recharge' => $data['total_recharge'],
            'withdraw' => $data['withdraw'],
            'total_withdraw' => $data['total_withdraw'],
            'consume' => $data['consume'],
            'total_consume' => $data['total_consume'],
            'refund' => $data['refund'],
            'total_refund' => $data['total_refund'],
            'expend' => $data['expend'],
            'total_expend' => $data['total_expend'],
            'income' => $data['income'],
            'total_income' => $data['total_income'],
        ];
    }
}
