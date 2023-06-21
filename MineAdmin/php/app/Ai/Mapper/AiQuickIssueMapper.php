<?php
declare(strict_types=1);


namespace App\Ai\Mapper;

use App\Ai\Model\AiQuickIssue;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 快捷问题
Mapper类
 */
class AiQuickIssueMapper extends AbstractMapper
{
    /**
     * @var AiQuickIssue
     */
    public $model;

    public function assignModel()
    {
        $this->model = AiQuickIssue::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        // 问题标题
        if (isset($params['title']) && $params['title'] !== '') {
            $query->where('title', 'like', '%'.$params['title'].'%');
        }

        // 问题描述
        if (isset($params['content']) && $params['content'] !== '') {
            $query->where('content', 'like', '%'.$params['content'].'%');
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