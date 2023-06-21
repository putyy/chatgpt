<?php
declare(strict_types=1);

namespace App\Ai\Api;

use App\Ai\Constants\ResponseCodeConst;
use App\Ai\Constants\VipConst;
use App\Ai\Model\AiUser;
use App\Ai\Service\AiImageMaterialService;
use App\Ai\Service\AiLoginService;
use App\Ai\Middleware\AuthMiddleware;
use App\Ai\Service\AiMineMenuGroupService;
use App\Ai\Service\AiUserService;
use App\Ai\Service\HelperService;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Mine\Exception\NormalStatusException;
use Mine\Helper\MineCode;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

#[Controller(prefix: "ai/api/user")]
#[Middleware(AuthMiddleware::class)]
class User extends BaseApi
{
    #[Inject]
    protected AiLoginService $loginService;

    #[Inject]
    protected AiUserService $userService;

    #[Inject]
    protected AiMineMenuGroupService $mineMenuGroupService;

    #[Inject]
    protected AiImageMaterialService $imageMaterialService;

    #[GetMapping("mine")]
    public function mine()
    {
        /**
         * @var AiUser $user
         */
        $user  = $this->userService->read($this->loginService->getId());
        return $this->success([
            'head_img'   => $user->head_img,
            'nick_name'  => $user->nick_name,
            'uid'        => $user->id,
            'mobile'     => $user->mobile,
            'vip'        => $user->vip,
            'vip_name'   => VipConst::getDesc($user->vip),
            'vip_ent_at' => $user->vip_ent_at ? strtotime($user->vip_ent_at) : 0,
            'amount'     => $user->wallet->balance ? HelperService::decode100($user->wallet->balance) : 0,
            'friend_num' => $this->userService->friendNum($user->id),
            'menus'      => $this->mineMenuGroupService->mineMenus(),
            'banner'     => $this->imageMaterialService->mine(1),
        ]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Throwable
     */
    #[PostMapping("edit")]
    public function edit(): ResponseInterface
    {
        $post = $this->request->post();
        if (!$this->checkParameter($post, ['mobile', 'head_img', 'nick_name'])) {
            throw new NormalStatusException('参数错误', ResponseCodeConst::PARAM_FAILED);
        }
        return $this->funCallbackRes(function () use ($post) {
            $this->userService->update($this->loginService->getId(), [
                'nick_name' => $post['nick_name'],
                'head_img' => HelperService::buildSavePath($post['head_img']),
                'mobile' => $post['mobile'],
            ]);
            return '';
        });
    }

    #[GetMapping("friends")]
    public function friends(): ResponseInterface
    {
        return $this->success([
            'list'=>$this->userService->friendList($this->request->all())
        ]);
    }
}