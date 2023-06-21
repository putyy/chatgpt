<?php
declare(strict_types=1);

namespace App\Ai\Service;

use Hyperf\Context\Context;
use Psr\SimpleCache\InvalidArgumentException;
use Xmo\JWTAuth\JWT;

class AiLoginService
{
    /**
     * @var JWT
     */
    protected JWT $jwt;

    /**
     * LoginUser constructor.
     * @param string $scene 场景，默认为default
     */
    public function __construct()
    {
        /* @var JWT $this- >jwt */
        $this->jwt = make(JWT::class)->setScene('ai');
    }

    /**
     * 获取Token
     * @param array $claims
     * @return string
     * @throws InvalidArgumentException
     */
    public function getToken(array $claims): string
    {
        return $this->jwt->getToken($claims);
    }

    /**
     * 验证token
     * @param string|null $token
     * @param string $scene
     * @return bool
     */
    public function check(?string $token = null, string $scene = 'default'): bool
    {
        try {
            if ($this->jwt->checkToken($token, $scene, true, true, true)) {
                return true;
            }
        } catch (InvalidArgumentException $e) {
//            throw new TokenException(t('jwt.no_token'));
            return false;
        } catch (\Throwable $e) {
//            throw new TokenException(t('jwt.no_login'));
            return false;
        }

        return false;
    }

    /**
     * 获取JWT对象
     * @return Jwt
     */
    public function getJwt(): Jwt
    {
        return $this->jwt;
    }

    /**
     * 获取当前登录用户信息
     * @param string|null $token
     * @return array
     */
    public function getInfo(?string $token = null): array
    {
        $info = Context::get(self::class.'_info');
        if (empty($info)){
            $info = $this->jwt->getParserData($token);
            Context::set(self::class.'_info', $info);
        }

        return $info;
    }

    /**
     * 获取当前登录用户ID
     * @return int
     */
    public function getId(): int
    {
        return $this->getInfo()['id'];
    }

    /**
     * 刷新token
     * @return string
     */
    public function refresh(): string
    {
        return $this->jwt->refreshToken();
    }
}