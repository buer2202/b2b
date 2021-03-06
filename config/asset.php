<?php
return [
    // 异常类，可自定义
    'exception_class' => \App\Exceptions\CustomException::class,

    // 交易类型，数字不能改
    'type' => [
        1 => '加款',
        2 => '提现',
        3 => '冻结',
        4 => '解冻',
        5 => '扣款',
        6 => '退款',
        7 => '支出',
        8 => '收入',
    ],

    // 交易子类型，可自定义
    'sub_type' => [
        11 => '线下加款',
        21 => '线下提现',
        31 => '提现冻结',
        41 => '拒绝提现解冻',
        51 => '人工扣款',
        61 => '人工退款',
        71 => '支出',
        81 => '收入',
    ],

    'add-money' => [
        'status' => [
            1 => '新建',
            2 => '财务审核',
            3 => '财务拒绝',
        ],

        // 支付类型
        'pay_type' => [
            1 => '线下加款',
            2 => '线上加款',
        ],
    ],

    'withdraw' => [
        'status' => [
            1 => '申请提现',
            2 => '部门审核',
            3 => '财务审核',
            4 => '财务拒绝',
            5 => '提现成功',
        ],

        // 支付类型
        'pay_type' => [
            0 => '未确定',
            1 => '线下打款',
            2 => '线上打款',
        ],
    ],

    // 人工操作
    'manual' => [
        // 扣款
        'deduct-money' => [
            'status' => [
                1 => '新建',
                2 => '财务审核',
                3 => '财务拒绝',
            ],
        ],

        // 退款
        'refund' => [
            'status' => [
                1 => '新建',
                2 => '财务审核',
                3 => '财务拒绝',
            ],
        ],
    ],

    'settlement_account' => [
        // 类型
        'type' => [
            1 => '支付宝',
            2 => '银行卡',
        ],

        'acc_type' => [
            1 => '对公',
            2 => '对私',
        ],
    ],
];
