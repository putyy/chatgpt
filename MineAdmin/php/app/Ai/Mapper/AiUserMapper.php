<?php
declare(strict_types=1);


namespace App\Ai\Mapper;

use App\Ai\Model\AiUser;
use App\Ai\Model\AiUserRelation;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 用户主表Mapper类
 */
class AiUserMapper extends AbstractMapper
{
    /**
     * @var AiUser
     */
    public $model;

    public function assignModel()
    {
        $this->model = AiUser::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {

        // 昵称
        if (isset($params['nick_name']) && $params['nick_name'] !== '') {
            $query->where('nick_name', 'like', '%'.$params['nick_name'].'%');
        }

        // 手机号
        if (isset($params['mobile']) && $params['mobile'] !== '') {
            $query->where('mobile', 'like', '%'.$params['mobile'].'%');
        }

        // vip等级
        if (isset($params['vip']) && $params['vip'] !== '') {
            $query->where('vip', '=', $params['vip']);
        }

        // vip到期时间
        if (isset($params['vip_ent_at']) && is_array($params['vip_ent_at']) && count($params['vip_ent_at']) == 2) {
            $query->whereBetween(
                'vip_ent_at',
                [ $params['vip_ent_at'][0], $params['vip_ent_at'][1] ]
            );
        }

        // 是否锁定:1正常,2锁定
        if (isset($params['is_lock']) && $params['is_lock'] !== '') {
            $query->where('is_lock', '=', $params['is_lock']);
        }

        // 创建时间
        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [ $params['created_at'][0], $params['created_at'][1] ]
            );
        }

        // 更新时间
        if (isset($params['updated_at']) && is_array($params['updated_at']) && count($params['updated_at']) == 2) {
            $query->whereBetween(
                'updated_at',
                [ $params['updated_at'][0], $params['updated_at'][1] ]
            );
        }
        return $query;
    }
}