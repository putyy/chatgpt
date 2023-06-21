<?php
declare(strict_types=1);

namespace App\Ai\Controller;

use App\Ai\Service\AiSettingService;
use App\Ai\Service\QiniuService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Mine\Annotation\Auth;
use Mine\Annotation\Permission;
use Mine\MineController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

#[Controller(prefix: "ai/setting"), Auth]
class AiSettingController extends MineController
{
    #[Inject]
    protected AiSettingService $settingService;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[GetMapping("index"), Permission("ai:setting, ai:setting:index")]
    public function index(): ResponseInterface
    {
        return $this->success(array_merge([
            'app_close_message' => $this->settingService->appClose(),
            'agreement_user' => $this->settingService->agreementUser(),
            'openai_proxy' => $this->settingService->openaiProxy(),

        ], $this->settingService->customer()));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[PostMapping("save"), Permission("ai:setting, ai:setting:save")]
    public function save(): ResponseInterface
    {
        $this->settingService->setAppClose($this->request->post('app_close_message', ''));
        $this->settingService->setAgreementUser($this->request->post('agreement_user', ''));
        $this->settingService->setCustomer($this->request->post('customer'));
        $this->settingService->setOpenaiProxy($this->request->post('openai_proxy'));
        return $this->success();
    }

    #[Inject]
    protected QiniuService $qiniuService;

    #[GetMapping("upload-token")]
    public function uploadToken(): ResponseInterface
    {
        return $this->success($this->qiniuService->token($this->request->query('scenes')));
    }
}