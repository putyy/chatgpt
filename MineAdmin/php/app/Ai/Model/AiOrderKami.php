<?php

declare(strict_types=1);

namespace App\Ai\Model;

use Mine\MineModel;

/**
 * @property int $oid 订单ID
 * @property int $kid 卡密ID
 */
class AiOrderKami extends MineModel
{
    public bool $incrementing = false;
    protected string $primaryKey = 'oid';
    public bool $timestamps = false;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_order_kami';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['oid', 'kid'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['oid' => 'integer', 'kid' => 'integer'];
}
