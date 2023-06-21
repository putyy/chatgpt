<?php
declare(strict_types=1);

namespace App\Ai\Api;

use App\Ai\Constants\OrderConst;
use App\Ai\Middleware\AuthMiddleware;
use App\Ai\Service\AiOrderService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Http\Message\ResponseInterface;

#[Controller(prefix: "ai/api/order")]
#[Middleware(AuthMiddleware::class)]
class Order extends BaseApi
{
    #[Inject]
    protected AiOrderService $orderService;

    #[GetMapping("list")]
    public function index(): ResponseInterface
    {
        $param = $this->request->all();
        $param['ord_type'] = [OrderConst::OPEN_VIP, OrderConst::MARKET];
        return $this->success([
            'list' => $this->orderService->orderList($param)
        ]);
    }

    #[PostMapping("kami-open-vip")]
    public function kamiOpenVip(): ResponseInterface
    {
        $this->orderService->kamiOpenVip($this->request->all());
        return $this->success();
    }
}