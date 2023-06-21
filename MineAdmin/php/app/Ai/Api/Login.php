<?php
declare(strict_types=1);

namespace App\Ai\Api;

use App\Ai\Model\AiUser;
use App\Ai\Model\AiUserRelation;
use App\Ai\Model\AiUserWallet;
use App\Ai\Service\AiLoginService;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

#[Controller(prefix: "ai/api/login")]
class Login extends BaseApi
{
    public const VERSIONS = '0.0.1';

    #[Inject]
    protected AiLoginService $loginService;

    #[GetMapping("index")]
    public function index()
    {
        try {
            $user_name = $this->request->query('user_name');
            $vid       = (int)$this->request->query('vid');
            $password  = $this->request->query('password');
            if (!$user_name || !$password){
                return $this->error('参数错误！');
            }
            /**
             * @var AiUser $user
             */
            $user     = AiUser::where(['mobile'=>$user_name, 'is_lock'=>1])->first();
            $password = md5($password);
            if (!empty($user) && $password !== $user->password){
                return $this->error('密码错误！');
            }

            if (empty($user)){
                if (empty($vid)) {
                    return $this->error('账号不存在！');
                }else{
                    /**
                     * @var AiUser $pUser
                     */
                    $pUser = AiUser::where(['id'=>$vid, 'is_lock'=>1])->select(['id'])->first();
                    if (empty($pUser)) {
                        return $this->error('邀请用户有误！');
                    }
                    // 注册
                    Db::beginTransaction();
                    try {
                        $user            = new AiUser();
                        $user->nick_name = '';
                        $user->head_img  = '';
                        $user->mobile    = $user_name;
                        $user->password  = $password;
                        $user->save();
                        AiUserRelation::insert([
                            'uid'       => $user->id,
                            'from_uid' => $pUser->id,
                        ]);
                        AiUserWallet::insert([
                            'uid' => $user->id,
                        ]);
                        Db::commit();
                    }catch (\Throwable $exception){
                        Db::rollBack();
                        return $this->error('注册失败！');
                    }
                }
            }

            $data            = [];
            $data['version'] = self::VERSIONS;
            $data['id']      = $user->id;
            $data['vip']     = $user->vip;
            $token           = $this->loginService->getToken($data);
            return $this->success([
                'token'        => $token,
                'version'      => self::VERSIONS,
                'check_status' => false,
                'login_time'   => time(),
            ]);
        }catch (\Throwable $exception){
            return $this->error($exception->getMessage());
        }
    }
}