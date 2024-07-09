<?php
declare(strict_types=1);


namespace App\Ai\Service;

use App\Ai\Constants\ResponseCodeConst;
use App\Ai\Constants\VipConst;
use App\Ai\Factory\AiRedisFactory;
use App\Ai\Mapper\AiUserMapper;
use App\Ai\Model\AiUser;
use App\Ai\Model\AiUserRelation;
use App\Ai\Model\AiUserWallet;
use Hyperf\DbConnection\Db;
use Mine\Abstracts\AbstractService;
use Mine\Exception\NormalStatusException;

/**
 * 用户主表服务类
 */
class AiUserService extends AbstractService
{
    /**
     * @var AiUserMapper
     */
    public $mapper;

    protected AiRedisFactory $redis;

    protected AiLoginService $loginService;

    protected AiVipService $vipService;

    public function __construct(AiUserMapper $mapper, AiRedisFactory $redis, AiLoginService $loginService,  AiVipService $vipService)
    {
        $this->mapper       = $mapper;
        $this->redis        = $redis;
        $this->loginService = $loginService;
        $this->vipService   = $vipService;
    }

    public function userList(array $params): array
    {
        $relation = new AiUserRelation();
        $wallet   = new AiUserWallet();
        $user     = $this->mapper->getModel();
        $query    = Db::table($user->getTable().' as a')
                ->leftJoin($relation->getTable().' as b', 'a.id', '=', 'b.uid')
                ->leftJoin($wallet->getTable().' as c', 'a.id', '=', 'c.uid')
                ->leftJoin($user->getTable().' as d', 'b.from_uid', '=', 'd.id')
                ->select(['a.*', 'b.from_uid', 'b.market_uid', 'c.balance', 'c.balance_total', 'd.nick_name as parent_nick_name']);
        // 昵称
        if (isset($params['nick_name']) && $params['nick_name'] !== '') {
            $query->where('a.nick_name', 'like', '%'.$params['nick_name'].'%');
        }

        // 手机号
        if (isset($params['mobile']) && $params['mobile'] !== '') {
            $query->where('a.mobile', 'like', '%'.$params['mobile'].'%');
        }

        // vip等级
        if (isset($params['vip']) && $params['vip'] !== '') {
            $query->where('a.vip', '=', $params['vip']);
        }

        // vip到期时间
        if (isset($params['vip_ent_at']) && is_array($params['vip_ent_at']) && count($params['vip_ent_at']) == 2) {
            $query->whereBetween(
                'a.vip_ent_at',
                [ $params['vip_ent_at'][0], $params['vip_ent_at'][1] ]
            );
        }

        // 是否锁定:1正常,2锁定
        if (isset($params['is_lock']) && $params['is_lock'] !== '') {
            $query->where('a.is_lock', '=', $params['is_lock']);
        }

        // 创建时间
        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'a.created_at',
                [ $params['created_at'][0], $params['created_at'][1] ]
            );
        }

        // 更新时间
        if (isset($params['updated_at']) && is_array($params['updated_at']) && count($params['updated_at']) == 2) {
            $query->whereBetween(
                'a.updated_at',
                [ $params['updated_at'][0], $params['updated_at'][1] ]
            );
        }

        // 昵称
        if (isset($params['from_uid']) && $params['from_uid'] !== '') {
            $query->where('b.from_uid', '=', $params['from_uid']);
        }

        if ($params['orderBy'] ?? false) {
            if (is_array($params['orderBy'])) {
                foreach ($params['orderBy'] as $key => $order) {
                    $query->orderBy($order, 'a.'.$params['orderType'][$key] ?? 'asc');
                }
            } else {
                $query->orderBy($params['orderBy'], 'a.'.$params['orderType'] ?? 'asc');
            }
        }

        $query = $query->paginate((int)$params['pageSize'] ?? $this->mapper->getModel()::PAGE_SIZE, ['*'], 'page', (int)$params['page'] ?? 1);
        $res   = $this->mapper->setPaginate($query);

        foreach ($res['items'] as $k=>$v) {
            $res['items'][$k]->head_img      = HelperService::buildSourceUrl($v->head_img);
            $res['items'][$k]->balance       = $v->balance ? HelperService::decode100($v->balance) : '0';
            $res['items'][$k]->balance_total = $v->balance_total ? HelperService::decode100($v->balance_total) : '0';
        }

        return $res;
    }

    public function update(mixed $id, array $data): bool
    {
        /**
         * @var AiUser $user
         */
        $user = $this->mapper->first(['mobile' => $data['mobile']]);
        if (!empty($user) && $user->id != $id) {
            throw new NormalStatusException('手机号已有用户使用', ResponseCodeConst::PARAM_FAILED);
        }
        return $this->mapper->update($id, $data);
    }

    /**
     * 单个或批量软删除数据
     * @param array $ids
     * @return bool
     */
    public function delete(array $ids): bool
    {
        if (!empty($ids)){
            $this->mapper->delete($ids);
            foreach ($ids as $id) {
                $this->redis->sAdd('ai_user_delete', $id);
            }
            $this->redis->expire('ai_user_delete', 86400);
        }
        return true;
    }

    public function isJustDelete(int $id): bool
    {
        return $this->redis->sIsMember('ai_user_delete', $id);
    }

    public function lock(int $id)
    {
        $user = $this->read($id);
        if ($user) {
            $user->is_lock = $user->is_lock === 1 ? 2 : 1;
            $user->save();
            if ($user->is_lock === 2) {
                $this->redis->sAdd('ai_user_lock', $id);
                $this->redis->expire('ai_user_lock', 86400);
            } else {
                $this->redis->sRem('ai_user_lock', $id);
            }
        }
    }

    public function isJustLock(int $id): bool
    {
        return $this->redis->sIsMember('ai_user_lock', $id);
    }

    public function friendNum(int $uid): int
    {
        return AiUserRelation::where(['from_uid' => $uid])->count('uid');
    }

    public function friendList(array $param){
        $register_time1 =
        $register_time2 = '';
        if (!empty($param['register_time'])) {
            list($register_time1, $register_time2) = explode(',', $param['register_time']);
        }
        $keywords       = $param['keyword'] ?? '';
        $last_id        = $param['last_id'] ?? 0;
        $uid            = $this->loginService->getId();
        $model          = new AiUserRelation();
        $userModel      = new AiUser();

        if (($param['cate_id'] ?? '1') === '2') {
            $model = $model->where('market_uid', '=', $uid)->where('from_uid','<>',$uid);
        }else{
            $model = $model->where('from_uid','=',$uid);
        }

        $model = $model->leftJoin($userModel->getTable().' as u', 'uid', '=', 'u.id');

        if ($last_id) {
            $model = $model->where('u.id', '<', $last_id);
        }

        $vip = $param['vip'] ?? -1;
        if ($vip > -1) {
            $model = $model->where('u.vip', '=', $vip);
        }

        if ($register_time1 && $register_time2) {
            $model = $model->where('u.created_at', '>', $register_time1.' 00:00:00')->where('u.created_at', '<', $register_time2.' 00:00:00');
        }

        if ($keywords) {
            $model = $model->where('u.nick_name', 'like', "%$keywords%");
        }

        return $model->orderBy('u.id', 'desc')
               ->where('u.is_lock','=', 1)
               ->select(['u.id','u.nick_name','u.head_img','u.mobile','u.vip','u.created_at'])
               ->limit(10)
               ->get()
               ->each(function ($user) {
                   $user->head_img = HelperService::buildSourceUrl($user->head_img);
                   $user->vip_name = VipConst::getDesc($user->vip);
               })
               ->toArray();
    }
}