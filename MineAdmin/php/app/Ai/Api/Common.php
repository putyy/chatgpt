<?php
declare(strict_types=1);

namespace App\Ai\Api;

use App\Ai\Constants\VipConst;
use App\Ai\Service\AiLoginService;
use App\Ai\Service\AiSettingService;
use App\Ai\Service\AiVipService;
use App\Ai\Middleware\AuthMiddleware;
use App\Ai\Constants\UploadSceneConst;
use App\Ai\Service\QiniuService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Psr\Http\Message\ResponseInterface;

#[Controller(prefix: "ai/api/public")]
#[Middleware(AuthMiddleware::class)]
class Common extends BaseApi
{
    #[Inject]
    protected QiniuService $qiniuService;

    #[Inject]
    protected AiLoginService $loginService;

    #[Inject]
    protected AiVipService $vipService;

    #[Inject]
    protected AiSettingService $settingService;

    /**
     * 全局场景值
     */
    #[GetMapping("init")]
    public function init(): ResponseInterface
    {
        $data = [];
        // 图片资源上传
        $data['scene'] = [
            'upload' => [
                'image'  => array_filter(UploadSceneConst::ImageScene, function ($key) {
                    return str_starts_with($key, 'ai_');
                }, ARRAY_FILTER_USE_KEY),
                // 远端访问主域名, 用于前端上传时判断是否本地文件
                'domain' => config('file.storage.qiniu.host'),
            ],
            'tutorials' => [],
            'agreement' => [
                'user' => $this->settingService->agreementUser(),
            ],
            'ads' => [],
        ];

        $data['other'] = [
            'copyright'     => 'Copyright © 1999 - ' . date('Y') . ' ByAi',
            'version'       => Login::VERSIONS,
            'customer_info' => $this->settingService->customer(),
        ];
        return $this->success($data);
    }

    #[GetMapping("upload-token")]
    public function uploadToken(): ResponseInterface
    {
        return $this->success($this->qiniuService->token($this->request->query('scenes')));
    }

    #[GetMapping("vip-config")]
    public function vipConfig(): ResponseInterface
    {
        [$config, $user] = $this->vipService->config($this->loginService->getId());
        $configCp = array_column(VipConst::config(), null, 'level');
        $data = [
            'equity' => [
                [
                    [
                        'text'  => '特权',
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP]['name'],
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP_ONE]['name'],
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP_TWO]['name'],
                        'color' => '',
                    ],
                ],
                [
                    [
                        'text'  => '使用时长(月)',
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP]['length'],
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP_ONE]['length'],
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP_TWO]['length'],
                        'color' => 'red',
                    ],
                ],
                [
                    [
                        'text'  => 'VIP抵扣包',
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP]['wrap_vip'],
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP_ONE]['wrap_vip'],
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP_TWO]['wrap_vip'],
                        'color' => 'red',
                    ],
                ],
                [
                    [
                        'text'  => '推广赚(%)',
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP]['income'],
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP_ONE]['income'],
                        'color' => '',
                    ],
                    [
                        'text'  => $configCp[VipConst::VIP_TWO]['income'],
                        'color' => 'red',
                    ],
                ],
                [
                    [
                        'text'  => '聊天上下文',
                        'color' => '',
                    ],
                    [
                        'text'  => '不支持',
                        'color' => '',
                    ],
                    [
                        'text'  => '支持',
                        'color' => '',
                    ],
                    [
                        'text'  => '支持',
                        'color' => 'red',
                    ],
                ],
                [
                    [
                        'text'  => '每日次数',
                        'color' => '',
                    ],
                    [
                        'text'  => '19',
                        'color' => '',
                    ],
                    [
                        'text'  => '无限',
                        'color' => '',
                    ],
                    [
                        'text'  => '无限',
                        'color' => 'red',
                    ],
                ],
                [
                    [
                        'text'  => '总价值',
                        'color' => '',
                    ],
                    [
                        'text'  => '1000+',
                        'color' => '',
                    ],
                    [
                        'text'  => '3000+',
                        'color' => '',
                    ],
                    [
                        'text'  => '5000+',
                        'color' => 'red',
                    ],
                ],
            ],
            'config' => $config,
            'user'   => $user->toArray(),
        ];

        return $this->success($data);
    }
}