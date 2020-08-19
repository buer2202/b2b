<?php
return [
    // 加入价格组的商品模型（要求商品模型必须有唯一id，name，status字段）
    'goods_model' => [
        App\Models\Goods::class => '基础商品模型',
    ],
];
