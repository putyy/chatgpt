<?php

declare(strict_types=1);

namespace App\Ai\Model;

use Hyperf\Database\Model\Relations\HasOne;
use Mine\MineModel;

/**
 * @property int $id 主键
 * @property int $uid 用户uid
 * @property int $prompt_id 模型ID
 * @property int $close 是否关闭:1正常,2关闭
 * @property int $share 是否分享:1关闭,2公开
 * @property string $created_at 创建时间
 * @property-read AiChatgptPrompts $prompt
 * @property-read AiChatMessage $firstMessage 
 * @property-read AiUser $user 
 */
class AiChatSession extends MineModel
{
    public bool $timestamps = false;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_chat_session';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'uid', 'prompt_id', 'close', 'share', 'created_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'uid' => 'integer', 'prompt_id' => 'integer', 'close' => 'integer', 'share' => 'integer'];

    public function prompt(): HasOne
    {
        return $this->hasOne(AiChatgptPrompts::class, 'id', 'prompt_id');
    }

    public function firstMessage(): HasOne
    {
        return $this->hasOne(AiChatMessage::class, 'sid', 'id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(AiUser::class, 'id', 'uid');
    }
}
