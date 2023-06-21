<?php
declare(strict_types=1);


namespace App\Ai\Service;

use App\Ai\Mapper\AiImageMaterialMapper;
use Mine\Abstracts\AbstractService;

/**
 * 图片素材服务类
 */
class AiImageMaterialService extends AbstractService
{
    /**
     * @var AiImageMaterialMapper
     */
    public $mapper;

    public function __construct(AiImageMaterialMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function mine(int $scene): array
    {
        $time = time();
        return $this->mapper->getModel()
             ->where('scene', '=', $scene)
             ->orderByDesc('sort')
             ->orderByDesc('id')
             ->select(['id','img_url','start_at','end_at','url'])
             ->get()
             ->filter(function ($item) use ($time){
                 return strtotime($item->start_at) < $time && strtotime($item->end_at) > $time;
             })
             ->toArray();
    }
}