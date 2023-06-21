<?php
declare(strict_types=1);


namespace App\Ai\Mapper;

use App\Ai\Model\AiImageMaterial;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 图片素材Mapper类
 */
class AiImageMaterialMapper extends AbstractMapper
{
    /**
     * @var AiImageMaterial
     */
    public $model;

    public function assignModel()
    {
        $this->model = AiImageMaterial::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        // 使用场景
        if (isset($params['scene']) && $params['scene'] !== '') {
            $query->where('scene', '=', $params['scene']);
        }

        // 备注
        if (isset($params['remark']) && $params['remark'] !== '') {
            $query->where('remark', 'like', '%'.$params['remark'].'%');
        }

        // 使用开始时间
        if (isset($params['start_at']) && is_array($params['start_at']) && count($params['start_at']) == 2) {
            $query->whereBetween(
                'start_at',
                [ $params['start_at'][0], $params['start_at'][1] ]
            );
        }

        // 使用结束时间
        if (isset($params['end_at']) && is_array($params['end_at']) && count($params['end_at']) == 2) {
            $query->whereBetween(
                'end_at',
                [ $params['end_at'][0], $params['end_at'][1] ]
            );
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