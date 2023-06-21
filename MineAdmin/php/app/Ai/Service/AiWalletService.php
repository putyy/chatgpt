<?php
declare(strict_types=1);

namespace App\Ai\Service;

use App\Ai\Constants\OrderConst;
use App\Ai\Constants\ResponseCodeConst;
use App\Ai\Constants\WalletConst;
use App\Ai\Model\AiOrder;
use App\Ai\Model\AiUser;
use App\Ai\Model\AiUserWallet;
use App\Ai\Model\AiUserWalletLog;
use Hyperf\DbConnection\Db;
use Mine\Exception\NormalStatusException;

class AiWalletService
{
    protected AiLoginService $loginService;

    public function __construct(AiLoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function info(): array
    {
        $res = AiUserWallet::where(['uid' => $this->loginService->getId()])->first();
        return [
            'balance' => isset($res->balance) ? HelperService::decode100($res->balance) : 0,
            'balance_total' => isset($res->balance_total) ? HelperService::decode100($res->balance_total) : 0,
        ];
    }

    public function withdrawal(array $param): void
    {
        /**
         * @var AiUserWallet $userWallet
         */
        $userWallet = AiUserWallet::where(['uid' => $this->loginService->getId()])->first();
        if (empty($userWallet)) {
            throw new NormalStatusException('账户信息错误', ResponseCodeConst::PARAM_FAILED);
        }
        $amount_price = HelperService::encode100((int)$param['amount']);
        if ($userWallet->balance < $amount_price){
            throw new NormalStatusException('账户余额不足', ResponseCodeConst::PARAM_FAILED);
        }

        /**
         * @var AiUser $user
         */
        $user = AiUser::where(['id'=>$userWallet->uid])->first();

        Db::beginTransaction();
        try {
            AiOrder::create([
                'uid'          => $userWallet->uid,
                'from_uid'     => $user->parentRelation->from_uid,
                'market_uid'   => $user->parentRelation->market_uid,
                'ord_sn'       => HelperService::createOrderCode($user->parentRelation->from_uid),
                'ord_type'     => OrderConst::WITHDRAWAL,
                'pay_type'     => OrderConst::WX_PAY,
                'status'       => OrderConst::WAIT_PAY,
                'total_price'  => $amount_price,
                'amount_price' => $amount_price,
                'content'      => '提现<<' . HelperService::decode100($amount_price) . '元>>',
                'remark'       => '',
            ]);
            AiUserWallet::where(['uid' => $userWallet->uid])->update([
                'balance' => Db::raw('`balance`-' . $amount_price),
            ]);
            Db::commit();
        }catch (\Throwable $exception){
            Db::rollBack();
            throw new NormalStatusException($exception->getMessage(), ResponseCodeConst::PARAM_FAILED);
        }
    }

    public function changeLogList(array $param): array
    {
        $create_time1 =
        $create_time2 = '';
        if (!empty($param['create_time'])) {
            list($create_time1, $create_time2) = explode(',', $param['create_time']);
        }
        $keywords     = $param['keyword'] ?? '';
        $direction    = (int)$param['direction'] ?? 0;
        $last_id      = $param['last_id'] ?? 0;
        $model        = new AiUserWalletLog();
        $model = $model->where([
            'uid' => $this->loginService->getId()
        ]);

        if ($direction){
            $model = $model->where('direction', '=', $direction === WalletConst::IN ? WalletConst::IN : WalletConst::OUT);
        }

        if ($create_time1 && $create_time2) {
            $model = $model->where('created_at', '>', $create_time1.' 00:00:00')
                ->where('created_at', '<', $create_time2.' 00:00:00');
        }

        if ($keywords) {
            $model = $model->where('remark', 'like', "%$keywords%");
        }

        if ($last_id) {
            $model = $model->where('id', '<', $last_id);
        }

        $scene = WalletConst::getGroupValueDescArr('scene');
        return $model->limit(15)->orderBy('id', 'desc')->get()
            ->each(function ($item) use ($scene){
                $item->scene_text = $scene[$item->scene];
                $item->balance_text = HelperService::decode100($item->balance);
            })
            ->toArray();
    }
}