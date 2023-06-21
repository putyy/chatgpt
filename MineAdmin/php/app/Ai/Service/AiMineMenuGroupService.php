<?php
declare(strict_types=1);


namespace App\Ai\Service;

use App\Ai\Mapper\AiMineMenuGroupMapper;
use App\Ai\Model\AiMineMenuGroup;
use Mine\Abstracts\AbstractService;

/**
 * 个人中心菜单分组服务类
 */
class AiMineMenuGroupService extends AbstractService
{
    /**
     * @var AiMineMenuGroupMapper
     */
    public $mapper;

    public function __construct(AiMineMenuGroupMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function mineMenus(): array
    {
        return $this->mapper->getModel()::with(['menus' => function ($query) {
                   $query->orderByDesc('sort')->orderByDesc('id');
               }])
               ->orderByDesc('sort')
               ->orderByDesc('id')
               ->get()
               ->toArray();
    }
}