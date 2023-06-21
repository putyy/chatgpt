<?php

declare(strict_types=1);

namespace App\Ai\Middleware;

use App\Ai\Service\AiLoginService;
use App\Ai\Api\Login;
use App\Ai\Constants\ResponseCodeConst;
use App\Ai\Service\AiSettingService;
use App\Ai\Service\AiUserService;
use Hyperf\Context\Context;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Mine\Exception\NormalStatusException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected HttpResponse $response;

    protected StdoutLoggerInterface $logger;

    protected AiLoginService $loginService;

    protected AiSettingService $settingService;

    protected AiUserService $userService;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
        $this->loginService = $container->get(AiLoginService::class);
        $this->settingService = $container->get(AiSettingService::class);
        $this->logger = $container->get(StdoutLoggerInterface::class);
        $this->userService = $container->get(AiUserService::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (false === $this->loginService->check(null, 'ai')) {
            throw new NormalStatusException('error', ResponseCodeConst::INVALID_TOKEN);
        }

        $data = $this->loginService->getInfo();
        if (($data['version'] ?? '') != Login::VERSIONS) {
            throw new NormalStatusException('error', ResponseCodeConst::CLEAR_ALL_CACHE);
        }

        // 检测系统是否关站
        if ($closeMsg = $this->settingService->appClose()) {
            throw new NormalStatusException($closeMsg, ResponseCodeConst::APP_CLOSE);
        }

        // 拦截刚删除的用户、锁定的账户
        if ($this->userService->isJustDelete($data['id'])) {
            throw new NormalStatusException('账号不存在', ResponseCodeConst::CLEAR_ALL_CACHE);
        }

        if ($this->userService->isJustLock($data['id'])) {
            throw new NormalStatusException('账号被锁定', ResponseCodeConst::ACCOUNT_LOCK);
        }

        Context::set(self::class . '_login_data', $data);
        return $handler->handle($request);
    }
}
