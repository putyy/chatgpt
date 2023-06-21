<?php
declare(strict_types=1);

namespace App\Ai\Factory;

use Hyperf\Redis\Redis;

class AiRedisFactory extends Redis
{
    protected string $poolName = 'default';
}