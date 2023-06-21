<?php

declare(strict_types=1);

namespace App\Ai\Model;

use Mine\MineModel;

/**
 * @property int $oid 订单ID
 * @property int $vip_level vip等级
 * @property string $wx_sn 微信订单号
 * @property string $mch_id 商户ID
 * @property \Carbon\Carbon $created_at 创建时间
 */
class AiOrderVip extends MineModel
{
    public bool $incrementing = false;
    protected string $primaryKey = 'oid';
    public bool $timestamps = false;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_order_vip';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['oid', 'vip_level', 'wx_sn', 'mch_id', 'created_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['oid' => 'integer', 'vip_level' => 'integer', 'created_at' => 'datetime'];
}
