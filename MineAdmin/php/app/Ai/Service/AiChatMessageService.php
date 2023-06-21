<?php
declare(strict_types=1);

namespace App\Ai\Service;

use App\Ai\Mapper\AiChatMessageMapper;
use App\Ai\Model\AiChatMessage;
use Mine\Abstracts\AbstractService;

/**
 * 聊天数据服务类
 */
class AiChatMessageService extends AbstractService
{
    /**
     * @var AiChatMessageMapper
     */
    public $mapper;

    public function __construct(AiChatMessageMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function messages(array $param): array
    {
        $slide   = $param['slide'] ?? 'prev';
        $last_id = $param['last_id'] ?? 0;
        $model   = $this->mapper->getModel();
        $model   = $model->where('sid', '=', $param['sid']);
        if ($slide === 'prev') {
            // 上一页
            if ($last_id) {
                $model = $model->where('id', '<', $last_id);
            }
        } else {
            if ($last_id) {
                $model = $model->where('id', '>', $last_id);
            }
        }
        return array_values($model->orderBy('id', 'desc')->limit(10)->get()->sortBy('id', SORT_ASC)->toArray());
    }

    public function shareMessageList(array $param): array
    {
        $model   = $this->mapper->getModel();
        $model   = $model->where('sid', '=', $param['sid']);
        $last_id = $param['last_id'] ?? 0;
        if ($last_id) {
            $model = $model->where('id', '>', $last_id);
        }
        return $model->orderBy('id')->limit(10)->get()->toArray();
    }
}