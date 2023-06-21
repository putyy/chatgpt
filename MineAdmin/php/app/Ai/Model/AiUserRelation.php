<?php

declare(strict_types=1);

namespace App\Ai\Model;

use Mine\MineModel;

/**
 * @property int $uid 用户UID
 * @property int $from_uid 上级
 * @property int $market_uid 上级营销部
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 */
class AiUserRelation extends MineModel
{
    public bool $incrementing = false;
    protected string $primaryKey = 'uid';
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_user_relation';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['uid', 'from_uid', 'market_uid', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['uid' => 'integer', 'from_uid' => 'integer', 'market_uid' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


}
