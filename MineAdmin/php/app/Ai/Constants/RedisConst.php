<?php
declare(strict_types=1);

namespace App\Ai\Constants;

class RedisConst
{
    /**
     * 用户uid绑定fd
     */
    const USER_TO_FD = 'ai_user_fd';

    /**
     * fa 绑定用户信息
     */
    const FD_TO_USER = 'ai_fd_user:';

    /**
     * 用户今天发言了多少次
     */
    const USER_SPOKE_TODAY = 'ai_user_spoke_today:';
}