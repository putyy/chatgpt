<?php
declare(strict_types=1);


namespace App\Ai\Mapper;

use App\Ai\Model\AiChatMessage;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 聊天数据Mapper类
 */
class AiChatMessageMapper extends AbstractMapper
{
    /**
     * @var AiChatMessage
     */
    public $model;

    public function assignModel()
    {
        $this->model = AiChatMessage::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        // 会话ID
        if (isset($params['sid']) && $params['sid'] !== '') {
            $query->where('sid', '=', $params['sid']);
        }

        // 内容
        if (isset($params['content']) && $params['content'] !== '') {
            $query->where('content', 'like', "%{$params['content']}%");
        }

        // 内容
        if (isset($params['reply_content']) && $params['reply_content'] !== '') {
            $query->where('reply_content', 'like', "%{$params['reply_content']}%");
        }

        // 创建时间
        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [ $params['created_at'][0], $params['created_at'][1] ]
            );
        }

        return $query;
    }
}