<?php
declare(strict_types=1);


namespace App\Ai\Mapper;

use App\Ai\Model\AiChatSession;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;
use Mine\Abstracts\AbstractMapper;

/**
 * 问答会话Mapper类
 */
class AiChatSessionMapper extends AbstractMapper
{
    /**
     * @var AiChatSession
     */
    public $model;

    public function assignModel()
    {
        $this->model = AiChatSession::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        // 主键
        if (isset($params['id']) && $params['id'] !== '') {
            $query->where('id', '=', $params['id']);
        }

        // 用户uid
        if (isset($params['uid']) && $params['uid'] !== '') {
            $query->where('uid', '=', $params['uid']);
        }

        // 模型ID
        if (isset($params['prompt_id']) && $params['prompt_id'] !== '') {
            $query->where('prompt_id', '=', $params['prompt_id']);
        }

        // 是否关闭:1正常,2关闭
        if (isset($params['close']) && $params['close'] !== '') {
            $query->where('close', '=', $params['close']);
        }

        // 是否分享:1关闭,2公开
        if (isset($params['share']) && $params['share'] !== '') {
            $query->where('share', '=', $params['share']);
        }

        return $query;
    }

    public function session($where): Model|Builder|null
    {
        return $this->model::where($where)->orderBy('id', 'desc')->first();
    }
}