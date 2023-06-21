<?php
declare(strict_types=1);


namespace App\Ai\Controller;

use App\Ai\Service\AiOrderService;
use App\Ai\Service\AiUserService;
use App\Ai\Request\AiUserRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Mine\Annotation\Auth;
use Mine\Annotation\OperationLog;
use Mine\Annotation\Permission;
use Mine\MineController;
use Psr\Http\Message\ResponseInterface;

/**
 * 用户主表控制器
 * Class AiUserController
 */
#[Controller(prefix: "ai/user"), Auth]
class AiUserController extends MineController
{
    /**
     * 业务处理服务
     * AiUserService
     */
    #[Inject]
    protected AiUserService $service;

    #[Inject]
    protected AiOrderService $orderService;
    /**
     * 列表
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("index"), Permission("ai:user, ai:user:index")]
    public function index(): ResponseInterface
    {
        return $this->success($this->service->userList($this->request->all()));
    }

    /**
     * 更新
     * @param int $id
     * @param AiUserRequest $request
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PutMapping("update/{id}"), Permission("ai:user:update"), OperationLog]
    public function update(int $id, AiUserRequest $request): ResponseInterface
    {
        return $this->service->update($id, $request->all()) ? $this->success() : $this->error();
    }

    /**
     * 读取数据
     * @param int $id
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("read/{id}"), Permission("ai:user:read")]
    public function read(int $id): ResponseInterface
    {
        return $this->success($this->service->read($id));
    }

    /**
     * 单个或批量删除数据到回收站
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[DeleteMapping("delete"), Permission("ai:user:delete"), OperationLog]
    public function delete(): ResponseInterface
    {
        return $this->service->delete((array) $this->request->input('ids', [])) ? $this->success() : $this->error();
    }

    /**
     * 更新
     * @param int $id
     * @param AiUserRequest $request
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PostMapping("open-vip/{id}"), Permission("ai:user:open-vip"), OperationLog]
    public function openVip(): ResponseInterface
    {
        $this->orderService->adminOpenVip($this->request->all());
        return $this->success();
    }

    #[PostMapping("lock/{id}"), Permission("ai:user:lock"), OperationLog]
    public function lock(int $id): ResponseInterface
    {
        $this->service->lock($id);
        return $this->success();
    }
}