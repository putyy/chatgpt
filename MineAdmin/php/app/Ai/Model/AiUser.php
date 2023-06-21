<?php

declare(strict_types=1);

namespace App\Ai\Model;

use App\Ai\Service\HelperService;
use Hyperf\Database\Model\Relations\HasOne;
use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id 主键
 * @property string $nick_name 昵称
 * @property string $mobile 手机号
 * @property int $vip vip等级
 * @property string $vip_ent_at vip到期时间
 * @property string $password 
 * @property int $is_lock 是否锁定:1正常,2锁定
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property-read AiUserRelation $parentRelation 
 * @property-read AiUserWallet $wallet 
 * @property mixed $head_img 头像
 */
class AiUser extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_user';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'nick_name', 'head_img', 'mobile', 'vip', 'vip_ent_at', 'password', 'is_lock', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'vip' => 'integer', 'is_lock' => 'integer', 'updated_by' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function parentRelation(): HasOne
    {
        return $this->hasOne(AiUserRelation::class, 'uid', 'id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(AiUserWallet::class, 'uid', 'id');
    }

    public function setHeadImgAttribute($value)
    {
        $this->attributes['head_img'] = HelperService::buildSavePath($value);
    }

    public function getHeadImgAttribute($value)
    {
        return HelperService::buildSourceUrl($value);
    }
}
