<?php
declare(strict_types=1);


namespace App\Ai\Mapper;

use App\Ai\Model\AiOpenaiKey;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * openai_keyMapper类
 */
class AiOpenaiKeyMapper extends AbstractMapper
{
    /**
     * @var AiOpenaiKey
     */
    public $model;

    public function assignModel()
    {
        $this->model = AiOpenaiKey::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        // openai_key
        if (isset($params['openai_key']) && $params['openai_key'] !== '') {
            $query->where('openai_key', 'like', '%'.$params['openai_key'].'%');
        }

        // 备注
        if (isset($params['remark']) && $params['remark'] !== '') {
            $query->where('remark', 'like', '%'.$params['remark'].'%');
        }

        // 主键
        if (isset($params['id']) && $params['id'] !== '') {
            $query->where('id', '=', $params['id']);
        }

        // 创建时间
        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [ $params['created_at'][0], $params['created_at'][1] ]
            );
        }

        // 更新时间
        if (isset($params['updated_at']) && is_array($params['updated_at']) && count($params['updated_at']) == 2) {
            $query->whereBetween(
                'updated_at',
                [ $params['updated_at'][0], $params['updated_at'][1] ]
            );
        }

        return $query;
    }
}