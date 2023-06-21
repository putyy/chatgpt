<?php
declare(strict_types=1);

namespace App\Ai\Constants;

use Pt\Constants\DescConst;

class VipConst extends DescConst
{
    /**
     * @Desc("免费会员")
     */
    const FREE = 0;

    /**
     * @Desc("VIP")
     */
    const VIP = 10;

    /**
     * @Desc("1星VIP")
     */
    const VIP_ONE = 20;

    /**
     * @Desc("2星VIP")
     */
    const VIP_TWO = 30;

    public static function config(): array
    {
        return [
            [
                'name' => 'VIP', // VIP名称
                'price' => '199', // 现价
                'price_pay' => '199', // 支付金额
                'price_old' => '198', // 原价
                'level' => 10, // 等级
                'length' => 12, // 时长(月)
                'income' => 0, // 收益%
                'wrap_vip' => 0, // VIP抵扣包
                'is_default' => false,// 默认选中
                'is_choose' => false,// 是否可选
                'btn_text' => '立即开通',
            ], [
                'name' => '一星',
                'price' => '299',
                'price_pay' => '299',
                'price_old' => '1198',
                'level' => 20,
                'length' => 36,
                'income' => 35,
                'wrap_vip' => 10,
                'is_default' => false,
                'is_choose' => false,
                'btn_text' => '立即开通',
            ], [
                'name' => '二星',
                'price' => '399',
                'price_pay' => '399',
                'price_old' => '2198',
                'level' => 30,
                'length' => 60,
                'income' => 50,
                'wrap_vip' => 20,
                'is_default' => false,
                'is_choose' => false,
                'btn_text' => '立即开通',
            ],
        ];
    }
}