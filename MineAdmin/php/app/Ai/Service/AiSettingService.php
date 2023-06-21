<?php
declare(strict_types=1);

namespace App\Ai\Service;

use App\Ai\Factory\AiRedisFactory;

class AiSettingService
{
    protected AiRedisFactory $redis;

    public function __construct(AiRedisFactory $redis){
        $this->redis = $redis;
    }

    const CACHE_KEY = 'ai_setting_';

    protected array $customer = [
        'head_img'   => '',
        'mobile'     => '',
        'user_name'  => '',
        'work_time'  => '',
        'wx_img_url' => '',
        'wx_no'      => ''
    ];

    public function customer(): array
    {
        $data = $this->redis->get(self::CACHE_KEY . 'customer');
        if ($data) {
            $data = \json_decode($data, true);
        }
        $data = array_merge($this->customer, $data ?: []);
        return $data;
    }

    public function setCustomer($data): array
    {
        $this->redis->set(self::CACHE_KEY . 'customer', \json_encode($data, JSON_UNESCAPED_UNICODE));
        return array_merge($this->customer, $data);
    }

    public function appClose()
    {
        return $this->redis->get(self::CACHE_KEY . 'app_close_message') ?: '' ;
    }

    public function setAppClose(string $v)
    {
        // 关站消息，有则为关站
        return $this->redis->set(self::CACHE_KEY . 'app_close_message', $v);
    }

    public function agreementUser()
    {
        return $this->redis->get(self::CACHE_KEY . 'agreement') ?: '';
    }

    public function setAgreementUser($v)
    {
        return $this->redis->set(self::CACHE_KEY . 'agreement', $v);
    }

    public function openaiProxy()
    {
        return $this->redis->get(self::CACHE_KEY . 'openai_proxy') ?: '';
    }

    public function setOpenaiProxy($v)
    {
        return $this->redis->set(self::CACHE_KEY . 'openai_proxy', $v);
    }
}