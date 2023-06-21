<?php

declare(strict_types=1);

namespace App\Ai\Model;

use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id 主键
 * @property int $sid 会话ID
 * @property string $content 内容
 * @property string $reply_content 内容
 * @property string $reply_at 回复时间
 * @property string $created_at 创建时间
 * @property string $deleted_at 删除时间
 */
class AiChatMessage extends MineModel
{
    use SoftDeletes;
    public bool $timestamps = false;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_chat_message';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'sid', 'content', 'reply_content', 'reply_at', 'created_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'sid' => 'integer'];
}
