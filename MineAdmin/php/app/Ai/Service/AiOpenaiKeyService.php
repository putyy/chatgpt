<?php
declare(strict_types=1);


namespace App\Ai\Service;

use App\Ai\Factory\AiRedisFactory;
use App\Ai\Mapper\AiOpenaiKeyMapper;
use App\Ai\Model\AiOpenaiKey;
use Mine\Abstracts\AbstractService;

/**
 * openai_key服务类
 */
class AiOpenaiKeyService extends AbstractService
{
    /**
     * @var AiOpenaiKeyMapper
     */
    public $mapper;

    protected AiRedisFactory $redis;

    public function __construct(AiOpenaiKeyMapper $mapper, AiRedisFactory $redis)
    {
        $this->mapper = $mapper;
        $this->redis = $redis;
    }

    public function batchAdd(array $data): bool
    {
        $keys = explode("\n", $data['content']);
        if (empty($keys)) {
            return false;
        }
        $saveAll = [];
        $time = date('Y-m-d H:i:s');
        foreach ($keys as $key=>$v) {
            $saveAll[$key]['openai_key'] = trim($v);
            $saveAll[$key]['remark']     = $data['remark'];
            $saveAll[$key]['created_at'] = $time;
            $saveAll[$key]['updated_at'] = $time;
        }
        $model = new AiOpenaiKey();
        $model->insert($saveAll);
        return true;
    }

    public function cacheAll(): bool
    {
        $this->redis->del('ai_openai_key_cache');
        $all = $this->mapper->get();
        if (empty($all)) return false;
        for ($i = 0; $i < 10; $i++) {
            foreach ($all as $item) {
                $this->redis->rPush('ai_openai_key_cache', $item['openai_key']);
            }
        }
        return true;
    }

    public function openAiKey()
    {
        $key = $this->redis->lPop('ai_openai_key_cache');
        $this->redis->rPush('ai_openai_key_cache', $key);
        return $key;
    }
}