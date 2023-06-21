<?php
declare(strict_types=1);


namespace App\Ai\Service;

use App\Ai\Constants\OrderConst;
use App\Ai\Constants\ResponseCodeConst;
use App\Ai\Constants\VipConst;
use App\Ai\Constants\WalletConst;
use App\Ai\Mapper\AiOrderMapper;
use App\Ai\Model\AiOrder;
use App\Ai\Model\AiOrderKami;
use App\Ai\Model\AiOrderVip;
use App\Ai\Model\AiPayKami;
use App\Ai\Model\AiUser;
use App\Ai\Model\AiUserWallet;
use App\Ai\Model\AiUserWalletLog;
use Hyperf\DbConnection\Db;
use Mine\Abstracts\AbstractService;
use Mine\Exception\NormalStatusException;

/**
 * 订单表服务类
 */
class AiOrderService extends AbstractService
{
    /**
     * @var AiOrderMapper
     */
    public $mapper;

    protected AiVipService $vipService;

    protected AiLoginService $loginService;

    public function __construct(AiOrderMapper $mapper, AiVipService $vipService, AiLoginService $loginService)
    {
        $this->mapper = $mapper;
        $this->vipService   = $vipService;
        $this->loginService = $loginService;
    }

    public function orderList(array $param): array
    {
        $create_time1 =
        $create_time2 = '';
        if (!empty($param['create_time'])) {
            list($create_time1, $create_time2) = explode(',', $param['create_time']);
        }

        $last_id      = $param['last_id'] ?? 0;
        $keywords     = $param['keyword'] ?? '';
        $model        = new AiOrder();
        $model = $model->where([
            'uid' => $this->loginService->getId(),
        ])->whereIn('ord_type', $param['ord_type']);

        if ($create_time1 && $create_time2) {
            $model = $model->where('created_at', '>', $create_time1 . ' 00:00:00')
                ->where('created_at', '<', $create_time2 . ' 00:00:00');
        }

        if ($last_id) {
            $model = $model->where('id', '<', $last_id);
        }

        if ($keywords) {
            $model = $model->where('content', 'like', "%$keywords%");
        }

        $ord_type = OrderConst::getGroupValueDescArr('ord_type');
        $status   = OrderConst::getGroupValueDescArr('status');
        $pay_type = OrderConst::getGroupValueDescArr('pay_type');
        return $model->limit(15)
            ->select(explode(',', 'id,uid,ord_sn,ord_type,pay_type,status,amount_price,content,created_at,pay_at'))
            ->orderBy('id', 'desc')->get()
            ->each(function ($item) use ($ord_type, $status, $pay_type) {
                $item->status_text       = $status[$item->status];
                $item->ord_type_text     = $ord_type[$item->ord_type];
                $item->pay_type_text     = $pay_type[$item->pay_type];
                $item->amount_price_text = HelperService::decode100($item->amount_price);
            })
            ->toArray();
    }

    public function kamiOpenVip(array $param)
    {
        /**
         * @var AiPayKami $kami
         */
        $kami = AiPayKami::where(['code' => $param['kami_code']])->first();
        if (empty($kami)) {
            throw new NormalStatusException('卡密不存在', ResponseCodeConst::PARAM_FAILED);
        }

        if (2 === $kami->status) {
            throw new NormalStatusException('卡密已失效', ResponseCodeConst::PARAM_FAILED);
        }

        if ($kami->uid && $kami->uid != $this->loginService->getId()) {
            throw new NormalStatusException('您无权使用该卡密', ResponseCodeConst::PARAM_FAILED);
        }

        /**
         * @var AiUser $user
         */
        [$vipConfigAll, $user] = $this->vipService->config($this->loginService->getId());
        unset($user->vip_name);
        $vipConfigAll = array_column($vipConfigAll, null, 'level');

        if (empty($vipConfigAll[$param['level']])) {
            throw new NormalStatusException('VIP信息有误', ResponseCodeConst::PARAM_FAILED);
        }

        $vipConfig = $vipConfigAll[$param['level']];

        if ($kami->price && $kami->price < $vipConfig['price_pay']) {
            throw new NormalStatusException('卡密金额不足', ResponseCodeConst::PARAM_FAILED);
        }

        if ($user->vip > $vipConfig['level']) {
            throw new NormalStatusException('VIP信息有误1', ResponseCodeConst::PARAM_FAILED);
        }
        Db::beginTransaction();
        try {
            $price = HelperService::encode100($vipConfig['price_pay']);
            $aiOrder = AiOrder::create([
                'uid'          => $user->id,
                'from_uid'     => $user->parentRelation->from_uid,
                'market_uid'   => $user->parentRelation->market_uid,
                'ord_sn'       => HelperService::createOrderCode($user->parentRelation->from_uid),
                'ord_type'     => OrderConst::OPEN_VIP,
                'pay_type'     => $price ? OrderConst::KAMI_PAY : OrderConst::KAMI_FREE,
                'status'       => OrderConst::SUCCESS_PAY,
                'total_price'  => $price,
                'amount_price' => $price,
                'content'      => '购买<<' . $vipConfig['name'] . '>>',
                'remark'       => '卡密支付',
            ]);
            $oid = $aiOrder->id;
            AiOrderVip::create([
                'oid' => $oid,
                'vip_level' => $vipConfig['level'],
            ]);
            AiOrderKami::create([
                'oid' => $oid,
                'kid' => $kami->id,
            ]);
            if ($price && !empty($user->parentRelation->from_uid)) {
                /**
                 * @var AiUser $parentInfo
                 */
                $parentInfo = AiUser::first(['id' => $user->parentRelation->from_uid]);
                $pVipConfig = $vipConfigAll[$parentInfo->vip] ?? [];
                if (!empty($pVipConfig) && ($parentInfo->vip ?? 0) > VipConst::VIP && $parentInfo->vip >= $vipConfig['level'] && $pVipConfig['income'] > 0) {
                    $income = HelperService::encode100($vipConfig['price_pay'] * ($pVipConfig['income'] / 100));
                    AiUserWallet::where(['uid' => $user->parentRelation->from_uid])->update([
                        'balance'       => Db::raw('`balance`+' . $income),
                        'balance_total' => Db::raw('`balance_total`+' . $income),
                    ]);

                    AiUserWalletLog::create([
                        'uid'        => $user->parentRelation->from_uid,
                        'oid'        => $oid,
                        'direction'  => WalletConst::IN,
                        'scene'      => WalletConst::PROMOTION,
                        'balance'    => $income,
                        'remark'     => 'user-'.$user->id.':购买<<' . $vipConfig['name'] . '>>获得' . $vipConfig['income'] . '%',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            $user->vip  = $vipConfig['level'];
            $vip_ent_at = $user->vip_ent_at ? strtotime($user->vip_ent_at) : 0;
            $time       = time();
            if ($vip_ent_at && $vip_ent_at > $time) {
                $vip_ent_at += $vipConfig['length'] * 2592000;
            } else {
                $vip_ent_at = $time + $vipConfig['length'] * 2592000;
            }
            $user->vip_ent_at = date('Y-m-d H:i:s', $vip_ent_at);
            $user->save();
            Db::commit();
        }catch (\Throwable $exception){
            Db::rollBack();
            throw new NormalStatusException($exception->getMessage(), ResponseCodeConst::PARAM_FAILED);
        }
    }

    public function adminOpenVip(array $data)
    {
        /**
         * @var AiUser $user
         */
        $user = $this->mapper->first(['id' => $data['id']]);
        if (empty($user)) {
            throw new NormalStatusException('用户不存在', ResponseCodeConst::PARAM_FAILED);
        }

        if ($user->vip > (int)$data['vip']) {
            throw new NormalStatusException('不能开通比用户原等级小的VIP', ResponseCodeConst::PARAM_FAILED);
        }

        $vipConfigAll = array_column(VipConst::config(), null, 'level');
        $vipConfig = $vipConfigAll[$data['vip']] ?? [];
        if (empty($vipConfig)) {
            throw new NormalStatusException('vip信息有误', ResponseCodeConst::PARAM_FAILED);
        }
        Db::beginTransaction();
        try {
            $price   = $data['price'] ? HelperService::encode100($data['price']) : 0;
            $aiOrder = AiOrder::create([
                'uid'          => $user->id,
                'from_uid'     => $user->parentRelation->from_uid,
                'market_uid'   => $user->parentRelation->market_uid,
                'ord_sn'       => HelperService::createOrderCode($user->parentRelation->from_uid),
                'ord_type'     => OrderConst::OPEN_VIP,
                'pay_type'     => $price ? OrderConst::ADMIN_PAY : OrderConst::ADMIN_FREE,
                'status'       => OrderConst::SUCCESS_PAY,
                'total_price'  => $price,
                'amount_price' => $price,
                'content'      => '购买<<' . $vipConfig['name'] . '>>',
                'remark'       => $data['remark'],
            ]);
            $oid = $aiOrder->id;
            AiOrderVip::create([
                'oid'       => $oid,
                'vip_level' => $vipConfig['level'],
            ]);
            if ($price && !empty($user->parentRelation->from_uid)) {
                /**
                 * @var AiUser $parentInfo
                 */
                $parentInfo = AiUser::first(['id' => $user->parentRelation->from_uid]);
                $pVipConfig = $vipConfigAll[$parentInfo->vip] ?? [];
                if (!empty($pVipConfig) && ($parentInfo->vip ?? 0) > VipConst::VIP && $parentInfo->vip >= $vipConfig['level'] && $pVipConfig['income'] > 0) {
                    $income = HelperService::encode100($data['price'] * ($pVipConfig['income'] / 100));
                    AiUserWallet::where(['uid' => $user->parentRelation->from_uid])->update([
                        'balance'       => Db::raw('`balance`+' . $income),
                        'balance_total' => Db::raw('`balance_total`+' . $income),
                    ]);

                    AiUserWalletLog::create([
                        'uid'        => $user->parentRelation->from_uid,
                        'oid'        => $oid,
                        'direction'  => WalletConst::IN,
                        'scene'      => WalletConst::PROMOTION,
                        'balance'    => $income,
                        'remark'     => 'user-'.$user->id.':购买<<' . $vipConfig['name'] . '>>获得' . $vipConfig['income'] . '%',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            $user->vip  = $vipConfig['level'];
            $vip_ent_at = $user->vip_ent_at ? strtotime($user->vip_ent_at) : 0;
            $time       = time();
            if ($vip_ent_at && $vip_ent_at > $time) {
                $vip_ent_at += $vipConfig['length'] * 2592000;
            } else {
                $vip_ent_at = $time + $vipConfig['length'] * 2592000;
            }
            $user->vip_ent_at = date('Y-m-d H:i:s', $vip_ent_at);
            $user->save();
            Db::commit();
        }catch (\Throwable $exception){
            Db::rollBack();
            throw new NormalStatusException($exception->getMessage(), ResponseCodeConst::PARAM_FAILED);
        }
    }
}