<?php
declare(strict_types=1);


namespace App\Ai\Mapper;

use App\Ai\Model\AiOrder;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 订单表Mapper类
 */
class AiOrderMapper extends AbstractMapper
{
    /**
     * @var AiOrder
     */
    public $model;

    public function assignModel()
    {
        $this->model = AiOrder::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        // 用户UID
        if (isset($params['uid']) && $params['uid'] !== '') {
            $query->where('uid', '=', $params['uid']);
        }

        // 订单号
        if (isset($params['ord_sn']) && $params['ord_sn'] !== '') {
            $query->where('ord_sn', 'like', '%'.$params['ord_sn'].'%');
        }

        // 订单类型: 1开通VIP 2提现 3成为营销部
        if (isset($params['ord_type']) && $params['ord_type'] !== '') {
            $query->where('ord_type', '=', $params['ord_type']);
        }

        // 支付方式: 1微信 2后台付费
        if (isset($params['pay_type']) && $params['pay_type'] !== '') {
            $query->where('pay_type', '=', $params['pay_type']);
        }

        // 订单状态 1未支付(待处理) 2已支付(已完成) 3失败
        if (isset($params['status']) && $params['status'] !== '') {
            $query->where('status', '=', $params['status']);
        }

        // 创建时间
        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [ $params['created_at'][0], $params['created_at'][1] ]
            );
        }

        // 订单完成时间
        if (isset($params['pay_at']) && is_array($params['pay_at']) && count($params['pay_at']) == 2) {
            $query->whereBetween(
                'pay_at',
                [ $params['pay_at'][0], $params['pay_at'][1] ]
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