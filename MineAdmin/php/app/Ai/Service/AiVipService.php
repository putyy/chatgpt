<?php
declare(strict_types=1);

namespace App\Ai\Service;

use App\Ai\Constants\OrderConst;
use App\Ai\Model\AiUser;
use App\Ai\Constants\VipConst;
use App\Ai\Model\AiOrder;

class AiVipService
{
    public function config(int $uid): array
    {
        $order = AiOrder::where([
                   'uid'      => $uid,
                   'ord_type' => 1,
                   'status'   => 2
               ])
               ->whereIn('pay_type', [OrderConst::WX_PAY, OrderConst::ADMIN_PAY, OrderConst::KAMI_PAY])
               ->select(['amount_price'])
               ->orderBy('id', 'desc')
               ->first();
        /**
         * @var AiUser $user
         */
        $user = AiUser::where(['id'=>$uid])->select(['id', 'vip', 'vip_ent_at'])->first();
        $user->vip_name = VipConst::getDesc($user->vip);
        $config = VipConst::config();

        foreach ($config as $k=>&$item) {
            if ($user->vip <= $item['level']) {
                // 当前等级小于等于配置等级低
                $item['is_choose'] = true;
            }

            if ($user->vip === $item['level']) {
                // 当前用户等级等于配置等级时为续费
                $item['btn_text'] = '立即续费';
            }

            if ($user->vip >0 && $user->vip < $item['level']) {
                if (!empty($order)) {
                    // 升级补差价 抵扣最近订单金额
                    $item['price_pay'] = HelperService::decode100($order->amount_price) > $item['price'] ? '￥1' : $item['price'];
                }
                $item['btn_text'] = '立即升级';
            }

            if ($user->vip === 0 && $k === 1){
                $item['is_default'] = true;
            }

            if ($user->vip === 10 && $k === 1){
                $item['is_default'] = true;
            }

            if ($user->vip === 20 && $k === 2){
                $item['is_default'] = true;
            }

            if ($user->vip === 30 && $k === 2) {
                $item['is_default'] = true;
            }
        }
        return [$config, $user];
    }

}