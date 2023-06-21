<?php
declare(strict_types=1);


namespace App\Ai\Controller;

use App\Ai\Service\AiChatgptPromptsService;
use App\Ai\Request\AiChatgptPromptsRequest;
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
 * chatgpt角色控制器
 * Class AiChatgptPromptsController
 */
#[Controller(prefix: "ai/chatgptPrompts"), Auth]
class AiChatgptPromptsController extends MineController
{
    /**
     * 业务处理服务
     * AiChatgptPromptsService
     */
    #[Inject]
    protected AiChatgptPromptsService $service;

    
    /**
     * 列表
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("index"), Permission("ai:chatgptPrompts, ai:chatgptPrompts:index")]
    public function index(): ResponseInterface
    {
        return $this->success($this->service->getPageList($this->request->all()));
    }

    /**
     * 更新
     * @param int $id
     * @param AiChatgptPromptsRequest $request
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PutMapping("update/{id}"), Permission("ai:chatgptPrompts:update"), OperationLog]
    public function update(int $id, AiChatgptPromptsRequest $request): ResponseInterface
    {
        return $this->service->update($id, $request->all()) ? $this->success() : $this->error();
    }

    /**
     * 新增
     * @param AiChatgptPromptsRequest $request
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PostMapping("save"), Permission("ai:chatgptPrompts:save"), OperationLog]
    public function save(AiChatgptPromptsRequest $request): ResponseInterface
    {
        return $this->success(['id' => $this->service->save($request->all())]);
    }

    /**
     * 读取数据
     * @param int $id
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("read/{id}"), Permission("ai:chatgptPrompts:read")]
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
    #[DeleteMapping("delete"), Permission("ai:chatgptPrompts:delete"), OperationLog]
    public function delete(): ResponseInterface
    {
        $ids = $this->request->input('ids', []);
        if (in_array(1, $ids)) {
            $this->error('id为1的角色不能删除！');
        }
        return $this->service->delete((array) $this->request->input('ids', [])) ? $this->success() : $this->error();
    }

}