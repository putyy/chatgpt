<?php

declare(strict_types=1);

namespace App\Ai\Model;

use App\Ai\Service\HelperService;
use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id 主键
 * @property int $gid 分组ID
 * @property string $name 分组名称
 * @property int $sort 排序
 * @property int $use_vip 使用权限限制 0全部
 * @property int $click_type 点击类型 1跳转 2调用函数
 * @property string $click_func 函数标识 小程序端提前封装
 * @property string $path 打开的页面路径
 * @property string $app_id 小程序appid
 * @property string $extra_data 需要传递给目标小程序的数据 json
 * @property string $env_version 要打开的小程序版本
 * @property string $short_link 小程序链接
 * @property int $is_lock 是否锁定:1正常,2锁定
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property mixed $icon 
 */
class AiMineMenu extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_mine_menu';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'gid', 'name', 'sort', 'use_vip', 'click_type', 'click_func', 'path', 'app_id', 'extra_data', 'env_version', 'short_link', 'icon', 'is_lock', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'gid' => 'integer', 'sort' => 'integer', 'use_vip' => 'integer', 'click_type' => 'integer', 'is_lock' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function setIconAttribute($value)
    {
        $this->attributes['icon'] = HelperService::buildSavePath($value);
    }

    public function getIconAttribute($value)
    {
        return HelperService::buildSourceUrl($value);
    }
}
