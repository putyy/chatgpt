<?php
declare(strict_types=1);

namespace App\Ai\Service;

use App\Ai\Mapper\AiChatgptPromptsMapper;
use Mine\Abstracts\AbstractService;

/**
 * chatgpt角色服务类
 */
class AiChatgptPromptsService extends AbstractService
{
    /**
     * @var AiChatgptPromptsMapper
     */
    public $mapper;

    const MODEL_LIST = [
        [
            'text'              => 'gpt-3.5-turbo-0613',
            'value'             => 0,
            'is_vip'            => false,
            // 每次对话上下文最多包含1500单词左右
            'max_tokens'        => 2048,
            'max_tokens_text'   => '每次对话上下文最多包含1500单词左右',
            // 温度设置（较高的温度会导致更多种类和不可预测的输出）
            'temperature'       => 0.5,
            // 在生成句子的时候加入惩罚项来限制重复单词
            'frequency_penalty' => 0,
            // 减少总体上使用频率较高的单词/短语的概率
            'presence_penalty'  => 0,
            // 上下文长度
            'context_length'  => 3,
        ],
        [
            'text'              => 'gpt-3.5-turbo-16k',
            'value'             => 1,
            'is_vip'            => true,
            'temperature'       => 1.0,
            'max_tokens'        => -1,
            'max_tokens_text'   => '每次对话上下文最多包含12000单词左右',
            'frequency_penalty' => 0,
            'presence_penalty'  => 0,
            'context_length'  => 3,
        ],
    ];

    public function __construct(AiChatgptPromptsMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}