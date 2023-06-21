<?php
declare(strict_types=1);


namespace App\Ai\Service;

use App\Ai\Mapper\AiMineMenuMapper;
use Mine\Abstracts\AbstractService;

/**
 * 个人中心菜单服务类
 */
class AiMineMenuService extends AbstractService
{
    /**
     * @var AiMineMenuMapper
     */
    public $mapper;

    public function __construct(AiMineMenuMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}