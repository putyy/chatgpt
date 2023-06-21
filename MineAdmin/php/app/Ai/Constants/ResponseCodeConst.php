<?php
declare(strict_types=1);

namespace App\Ai\Constants;

class ResponseCodeConst
{
    const INVALID_TOKEN   = 10001; // token 无效
    const ACCOUNT_LOCK    = 10002; // 账号被锁定
    const REDIRECT_LOGIN  = 10003; // 跳转到登录页
    const APP_CLOSE       = 10004; // 关站维护
    const CLEAR_ALL_CACHE = 10005; // 清空所有缓存数据
    const PARAM_FAILED   = 10006; // 参数错误
}