<?php
declare(strict_types=1);

namespace App\Ai\Constants;

use Pt\Constants\DescConst;

class WalletConst extends DescConst
{
    /**
     * @Desc("收入")
     * @Group("direction")
     */
    const IN = 1;

    /**
     * @Desc("支出")
     * @Group("direction")
     */
    const OUT = 2;

    /**
     * @Desc("推广获益")
     * @Group("scene")
     */
    const PROMOTION = 1;

    /**
     * @Desc("提现")
     * @Group("scene")
     */
    const WITHDRAWAL = 2;
}