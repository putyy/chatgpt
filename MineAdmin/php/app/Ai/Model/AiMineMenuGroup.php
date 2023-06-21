<?php

declare(strict_types=1);

namespace App\Ai\Model;

use \App\Ai\Model\AiMineMenu;
use Hyperf\Database\Model\Relations\HasMany;
use Mine\MineModel;

/**
 * @property int $id 主键
 * @property string $name 分组名称
 * @property int $sort 排序
 * @property int $is_lock 是否锁定:1正常,锁定
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property-read \Hyperf\Database\Model\Collection|AiMineMenu[] $menus 
 */
class AiMineMenuGroup extends MineModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_mine_menu_group';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'name', 'sort', 'is_lock', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'sort' => 'integer', 'is_lock' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    public function menus(): HasMany
    {
        return $this->hasMany(AiMineMenu::class, 'gid', 'id');
    }
}
