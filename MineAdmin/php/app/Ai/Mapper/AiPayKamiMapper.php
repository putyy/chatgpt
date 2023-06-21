<?php
declare(strict_types=1);


namespace App\Ai\Mapper;

use App\Ai\Model\AiPayKami;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 卡密Mapper类
 */
class AiPayKamiMapper extends AbstractMapper
{
    /**
     * @var AiPayKami
     */
    public $model;

    public function assignModel()
    {
        $this->model = AiPayKami::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        // 主键
        if (isset($params['id']) && $params['id'] !== '') {
            $query->where('id', '=', $params['id']);
        }

        // 绑定用户
        if (isset($params['uid']) && $params['uid'] !== '') {
            $query->where('uid', '=', $params['uid']);
        }

        // 卡密号
        if (isset($params['code']) && $params['code'] !== '') {
            $query->where('code', 'like', '%'.$params['code'].'%');
        }

        // 状态 1未使用 2已使用
        if (isset($params['status']) && $params['status'] !== '') {
            $query->where('status', '=', $params['status']);
        }

        // 备注
        if (isset($params['remark']) && $params['remark'] !== '') {
            $query->where('remark', 'like', '%'.$params['remark'].'%');
        }

        // 创建时间
        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [ $params['created_at'][0], $params['created_at'][1] ]
            );
        }

        // 使用时间
        if (isset($params['use_at']) && is_array($params['use_at']) && count($params['use_at']) == 2) {
            $query->whereBetween(
                'use_at',
                [ $params['use_at'][0], $params['use_at'][1] ]
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