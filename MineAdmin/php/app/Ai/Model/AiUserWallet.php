<?php

declare(strict_types=1);

namespace App\Ai\Model;

use Mine\MineModel;

/**
 * @property int $uid 用户UID
 * @property int $balance 余额
 * @property int $balance_total 总收入
 */
class AiUserWallet extends MineModel
{
    public bool $incrementing = false;
    protected string $primaryKey = 'uid';
    public bool $timestamps = false;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_user_wallet';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['uid', 'balance', 'balance_total'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['uid' => 'integer'];
}
