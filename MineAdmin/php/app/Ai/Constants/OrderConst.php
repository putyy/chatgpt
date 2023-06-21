<?php

namespace App\Ai\Constants;

use Pt\Constants\DescConst;

class OrderConst extends DescConst
{
    /**
     * @Desc("购买VIP")
     * @Group("ord_type")
     */
    const OPEN_VIP = 1;

    /**
     * @Desc("提现")
     * @Group("ord_type")
     */
    const WITHDRAWAL = 2;

    /**
     * @Desc("成为营销部")
     * @Group("ord_type")
     */
    const MARKET = 3;

    /**
     * @Desc("微信支付")
     * @Group("pay_type")
     */
    const WX_PAY = 1;

    /**
     * @Desc("后台免费")
     * @Group("pay_type")
     */
    const ADMIN_FREE = 2;

    /**
     * @Desc("后台付费")
     * @Group("pay_type")
     */
    const ADMIN_PAY = 3;

    /**
     * @Desc("免费卡密")
     * @Group("pay_type")
     */
    const KAMI_FREE = 4;

    /**
     * @Desc("付费卡密")
     * @Group("pay_type")
     */
    const KAMI_PAY = 5;

    /**
     * @Desc("待支付-待处理")
     * @Group("status")
     */
    const WAIT_PAY = 1;

    /**
     * @Desc("已支付-已完成")
     * @Group("status")
     */
    const SUCCESS_PAY = 2;

    /**
     * @Desc("支付失败-失败")
     * @Group("status")
     */
    const SUCCESS_ERR = 3;
}