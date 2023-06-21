<?php

declare(strict_types=1);

namespace App\Ai\Model;

use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id 主键
 * @property int $uid 用户UID
 * @property int $oid 订单ID
 * @property int $direction 类型:1收入,2支出
 * @property int $balance 余额
 * @property int $scene 变动场景: 1推广获益
 * @property string $remark 备注
 * @property \Carbon\Carbon $created_at 创建时间
 * @property string $deleted_at 删除时间
 */
class AiUserWalletLog extends MineModel
{
    use SoftDeletes;
    public bool $timestamps = false;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_user_wallet_log';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'uid', 'oid', 'direction', 'balance', 'scene', 'remark', 'created_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'uid' => 'integer', 'oid' => 'integer', 'direction' => 'integer', 'balance' => 'integer', 'scene' => 'integer', 'created_at' => 'datetime'];
}
