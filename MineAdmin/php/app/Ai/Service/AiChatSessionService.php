<?php
declare(strict_types=1);


namespace App\Ai\Service;

use App\Ai\Constants\ResponseCodeConst;
use App\Ai\Mapper\AiChatSessionMapper;
use App\Ai\Model\AiChatSession;
use Mine\Abstracts\AbstractService;
use Mine\Exception\NormalStatusException;

/**
 * 问答会话服务类
 */
class AiChatSessionService extends AbstractService
{
    /**
     * @var AiChatSessionMapper
     */
    public $mapper;

    protected AiLoginService $loginService;

    public function __construct(AiChatSessionMapper $mapper, AiLoginService $loginService)
    {
        $this->mapper = $mapper;
        $this->loginService = $loginService;
    }

    /**
     * 获取会话
     * @param array $param
     * @return array
     */
    public function session(array $param): array
    {
        $where          = [];
        $where['uid']   = $this->loginService->getId();
        $where['close'] = 1;
        if ($prompt_id = ($param['prompt_id'] ?? 0)) {
            $where['prompt_id'] = $prompt_id;
        }

        /**
         * @var $res AiChatSession
         */
        $res = $this->mapper->session($where);
        $isNew = false;
        if (empty($res)) {
            // 没有回话则创建会话
            $res      = new \stdClass();
            $res->prompt_id = $prompt_id ?: 1;
            $res->id = $this->mapper->save([
                'uid' => $this->loginService->getId(),
                'prompt_id' => $res->prompt_id,
                'created_at' => date("Y-m-d H:i:s")
            ]);
            $isNew = true;
        }
        return [
            'sid'    => $res->id,
            'prompt_id'    => $res->prompt_id,
            'is_new' => $isNew
        ];
    }

    public function sessionClose(array $param): int
    {
        if (empty($param['sid']) || empty($param['prompt_id'])) {
            throw new NormalStatusException('参数错误', ResponseCodeConst::PARAM_FAILED);
        }

        $this->mapper->updateByCondition(['id' => $param['sid'], 'uid' => $this->loginService->getId()], [
            'close' => 2
        ]);
        return $this->mapper->save([
            'uid' => $this->loginService->getId(),
            'prompt_id' => $param['prompt_id'],
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }

    public function sessionHistory(array $param): array
    {
        $where        = [];
        $where['uid'] = $this->loginService->getId();
        $model        = $this->mapper->getModel();
        $id           = $param['last_id'] ?? 0;
        if ($id) {
            $model = $model->where('id', '<', $id);
        }
        return $model->with('firstMessage')->where($where)->orderBy('id', 'desc')->limit(15)->get()->toArray();
    }

    public function sessionShareList(array $param): array
    {
        $where          = [];
        $where['share'] = 2;
        $model          = $this->mapper->getModel();
        $id             = $param['last_id'] ?? 0;
        if ($id) {
            $model = $model->where('id', '<', $id);
        }
       return $model->with('firstMessage')->with(['user' => function ($query) {
                  $query->select(['id', 'nick_name', 'head_img']);
              }])
              ->where($where)
              ->orderBy('id', 'desc')
              ->limit(10)
              ->get()
              ->toArray();
    }
}