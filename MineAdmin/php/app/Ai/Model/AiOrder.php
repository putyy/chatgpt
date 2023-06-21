<?php

declare(strict_types=1);

namespace App\Ai\Model;

use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id 主键
 * @property int $uid 用户UID
 * @property int $from_uid 上级UID
 * @property int $market_uid 营销部UID
 * @property string $ord_sn 订单号
 * @property int $ord_type 订单类型: 1开通VIP 2提现 3成为营销部
 * @property int $pay_type 支付方式: 1微信 2后台付费
 * @property int $status 订单状态 1未支付(待处理) 2已支付(已完成) 3失败
 * @property int $total_price 总金额
 * @property int $amount_price 实际金额
 * @property string $content 订单描述
 * @property string $remark 备注
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property string $pay_at 订单完成时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AiOrder extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_order';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'uid', 'from_uid', 'market_uid', 'ord_sn', 'ord_type', 'pay_type', 'status', 'total_price', 'amount_price', 'content', 'remark', 'created_by', 'updated_by', 'created_at', 'pay_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'uid' => 'integer', 'from_uid' => 'integer', 'market_uid' => 'integer', 'ord_type' => 'integer', 'pay_type' => 'integer', 'status' => 'integer', 'total_price' => 'integer', 'amount_price' => 'integer', 'created_by' => 'integer', 'updated_by' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
