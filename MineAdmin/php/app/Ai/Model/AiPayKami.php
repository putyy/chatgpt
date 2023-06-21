<?php

declare(strict_types=1);

namespace App\Ai\Model;

use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id 主键
 * @property int $uid 绑定用户
 * @property int $price 价格
 * @property string $code 卡密号
 * @property int $status 状态 1未使用 2已使用
 * @property string $remark 备注
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property string $use_at 使用时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AiPayKami extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_pay_kami';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'uid', 'price', 'code', 'status', 'remark', 'created_by', 'updated_by', 'created_at', 'use_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'uid' => 'integer', 'price' => 'integer', 'status' => 'integer', 'created_by' => 'integer', 'updated_by' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
