<?php
declare(strict_types=1);


namespace App\Ai\Service;

use App\Ai\Mapper\AiQuickIssueMapper;
use Mine\Abstracts\AbstractService;

/**
 * 快捷问题
服务类
 */
class AiQuickIssueService extends AbstractService
{
    /**
     * @var AiQuickIssueMapper
     */
    public $mapper;

    public function __construct(AiQuickIssueMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}