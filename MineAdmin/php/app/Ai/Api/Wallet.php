<?php
declare(strict_types=1);

namespace App\Ai\Api;

use App\Ai\Constants\OrderConst;
use App\Ai\Middleware\AuthMiddleware;
use App\Ai\Service\AiOrderService;
use App\Ai\Service\AiWalletService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Http\Message\ResponseInterface;

#[Controller(prefix: "ai/api/wallet")]
#[Middleware(AuthMiddleware::class)]
class Wallet extends BaseApi
{
    #[Inject]
    protected AiWalletService $walletService;

    #[Inject]
    protected AiOrderService $orderService;

    #[GetMapping("index")]
    public function index(){
        return $this->success($this->walletService->info());
    }

    #[GetMapping("change-log-list")]
    public function changeLogList(): ResponseInterface
    {
        return $this->success([
            'list'=>$this->walletService->changeLogList($this->request->all())
        ]);
    }

    #[GetMapping("withdrawal-list")]
    public function withdrawalList(): ResponseInterface
    {
        $param = $this->request->all();
        $param['ord_type'] = [OrderConst::WITHDRAWAL];
        return $this->success([
            'list'=>$this->orderService->orderList($param)
        ]);
    }

    #[PostMapping("withdrawal")]
    public function withdrawal(): ResponseInterface
    {
        $this->walletService->withdrawal($this->request->all());
        return $this->success();
    }
}