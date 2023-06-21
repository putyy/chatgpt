<?php
declare(strict_types=1);


namespace App\Ai\Mapper;

use App\Ai\Model\AiMineMenu;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 个人中心菜单Mapper类
 */
class AiMineMenuMapper extends AbstractMapper
{
    /**
     * @var AiMineMenu
     */
    public $model;

    public function assignModel()
    {
        $this->model = AiMineMenu::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        
        // 分组名称
        if (isset($params['name']) && $params['name'] !== '') {
            $query->where('name', 'like', '%'.$params['name'].'%');
        }

        // 是否锁定:1正常,2锁定
        if (isset($params['is_lock']) && $params['is_lock'] !== '') {
            $query->where('is_lock', '=', $params['is_lock']);
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