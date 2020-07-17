<?php

namespace App\Exports\Admin;

use App\Models\UserAddMoneyOrder;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserAddMoney implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return  UserAddMoneyOrder::orderBy('id', 'desc')
            ->when($this->request->start_time, function ($query) {
                return $query->where('created_at', '>=', $this->request->start_time);
            })
            ->when($this->request->end_time, function ($query) {
                return $query->where('created_at', '<=', Carbon::parse($this->request->end_time)->endOfDay());
            })
            ->when($this->request->no, function ($query) {
                return $query->where('no', $this->request->no);
            })
            ->when($this->request->user_id, function ($query) {
                return $query->where('user_id', $this->request->user_id);
            })
            ->when($this->request->status, function ($query) {
                return $query->where('status', $this->request->status);
            });
    }

    public function headings(): array
    {
        return [
            '加款单号',
            '外部单号',
            '类型',
            '收款账号',
            '状态',
            '金额',
            '用户id',
            '创建人',
            '审核人',
            '审核时间',
            '备注',
            '创建时间',
            '更新时间',
        ];
    }

    /**
    * @var Invoice $invoice
    */
    public function map($data): array
    {
        $config = config('asset.add-money');
        return [
            $data->no,
            $data->external_order_id,
            $config['pay_type'][$data->pay_type],
            $data->receive_account,
            $config['status'][$data->status],
            $data->fee,
            $data->user_id,
            $data->created_by,
            $data->auditd_by,
            $data->auditd_at,
            $data->remark,
            $data->created_at,
            $data->updated_at,
        ];
    }
}
