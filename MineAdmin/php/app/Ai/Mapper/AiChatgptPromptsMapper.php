<?php
declare(strict_types=1);


namespace App\Ai\Mapper;

use App\Ai\Model\AiChatgptPrompts;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * chatgpt角色Mapper类
 */
class AiChatgptPromptsMapper extends AbstractMapper
{
    /**
     * @var AiChatgptPrompts
     */
    public $model;

    public function assignModel()
    {
        $this->model = AiChatgptPrompts::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        // 角色名称
        if (isset($params['act']) && $params['act'] !== '') {
            $query->where('act', 'like', '%'.$params['act'].'%');
        }

        // 角色说明
        if (isset($params['prompt']) && $params['prompt'] !== '') {
            $query->where('prompt', 'like', '%'.$params['prompt'].'%');
        }

        return $query;
    }
}