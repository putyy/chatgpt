<?php
declare(strict_types=1);

namespace App\Ai\Api;

use App\Ai\Constants\RedisConst;
use App\Ai\Constants\VipConst;
use App\Ai\Factory\AiRedisFactory;
use App\Ai\Service\AiChatgptPromptsService;
use App\Ai\Service\AiChatMessageService;
use App\Ai\Service\AiLoginService;
use App\Ai\Service\AiOpenaiKeyService;
use App\Ai\Service\AiSettingService;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Engine\WebSocket\Opcode;
use Hyperf\Server\ServerFactory;
use Orhanerday\OpenAi\OpenAi;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\WebSocket\Server;
use Swoole\WebSocket\Server as WebSocketServer;
use Psr\Container\ContainerInterface;

class Websocket implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    #[Inject]
    protected AiLoginService $loginService;

    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected AiRedisFactory $redis;

    #[Inject]
    protected AiOpenaiKeyService $openAiService;

    #[Inject]
    protected AiSettingService $settingService;

    #[Inject]
    protected AiChatgptPromptsService $chatgptPromptsService;

    #[Inject]
    protected AiChatMessageService $chatMessageService;

    public function onMessage($server, $frame): void
    {
        if($frame->opcode == Opcode::PING) {
            // 如果使用协程 Server，在判断是 PING 帧后，需要手动处理，返回 PONG 帧。
            // 异步风格 Server，可以直接通过 Swoole 配置处理，详情请见 https://wiki.swoole.com/#/websocket_server?id=open_websocket_ping_frame
            $server->push('', Opcode::PONG);
            return;
        }
        $data = \json_decode($frame->data);
        // todo {model: "1:gpt3.5问答模式 2文心一言问答模式 3通义千问问答模式", role_id: 1, message:"",}
        switch ($data->type){
            case 'ping':
                $server->push($frame->fd, '{"type":"ping","content":"ok"}');
                break;
            case 'message':
                $this->messageHandle($frame->fd, $data);
                break;
        }
    }

    public function messageHandle($fd, $data)
    {
        switch ($data->model->index){
            case 0:
            case 1:
                // chatgpt 3.5
                // todo 存入消息，请求openapi 返回结果 结果入表
                \Hyperf\Coroutine\go(function () use($fd, $data){
                    $this->chatgpt($fd, $data);
                });
                break;
        }

    }

    protected function chatgpt($fd, $data){
        try {
            /**
             * @var WebSocketServer $server
             */
            $server  = $this->container->get(ServerFactory::class)->getServer()->getServer();
            // todo 验证会话id是否属于当前用户uid
            if (mb_strlen($data->content) > 2048) {
                $server->push($fd, '{"type":"error","content":"您输入的内容过长","status":true}');
                return;
            }
            $user = $this->redis->hGetAll(RedisConst::FD_TO_USER . $fd);
            // 免费会员限制会话
            $time = time();
            $todayKey = RedisConst::USER_SPOKE_TODAY . date('Ymd');
            $incr = $this->redis->zIncrBy($todayKey, 1, $user['uid']);
            $this->redis->expire($todayKey, 86400);
            if ($user['vip'] === VipConst::FREE && $incr > 10) {
                $server->push($fd, '{"type":"error","content":"您今日已经超过免费会员次数了哦！","status":true}');
                return;
            }

            $prompt = $this->chatgptPromptsService->mapper->first(['id'=>$data->prompt_id], ['id', 'act', 'prompt']);

            $opts = [
                'model'             => AiChatgptPromptsService::MODEL_LIST[$data->model->index]['text'],
                'messages'          => [],
                'temperature'       => $data->model->temperature,
                'frequency_penalty' => $data->model->frequency_penalty,
                'presence_penalty'  => $data->model->presence_penalty,
                'stream'            => true,
            ];

            if (AiChatgptPromptsService::MODEL_LIST[$data->model->index]['is_vip'] && !$user['vip']) {
                $opts['model'] = AiChatgptPromptsService::MODEL_LIST[0]['text'];
                $opts['max_tokens'] = 2048;
            } else if (!$user['vip']) {
                $opts['max_tokens'] = 2048;
            }

            $opts['messages'][] = ['role' => 'system', 'content' => $prompt->prompt];

            if (!empty($data->context) && $user['vip'] > VipConst::FREE){
                $i = 0;
                foreach ($data->context as $msg) {
                    $opts['messages'][] = [
                        'role'    => 'user',
                        'content' => $msg->content,
                    ];
                    $opts['messages'][] = [
                        'role'    => 'assistant',
                        'content' => $msg->reply_content,
                    ];
                    if (++$i > 6) {
                        break;
                    }
                }
            }

            $opts['messages'][] = [
                'role'    => 'user',
                'content' => $data->content,
            ];
            $key     = $this->openAiService->openAiKey();
            $openAI  = new OpenAi($key);
            $baseUrl = $this->settingService->openaiProxy();
            $baseUrl && $openAI->setBaseURL($baseUrl);
            $openAI->setHeader(["Content-Type"=>"text/event-stream"]);
            // 本次所有结果
            $replyContent = '';
            $openAI->chat($opts, function ($curl_info, $data) use ($fd, $server, &$replyContent) {
                $datas = explode('data: ', $data);
                foreach ($datas as $dataStr) {
                    $arrayData = json_decode($dataStr, true);
                    if ($arrayData) {
                        if (isset($arrayData['choices'][0]['delta']['content'])) {
                            $replyContent .= ($content = $arrayData['choices'][0]['delta']['content'] ?? '');
                            $server->push($fd, json_encode([
                                'type'    => 'message',
                                'content' => $content,
                                'status'  => false,
                            ], JSON_UNESCAPED_UNICODE));
                        } elseif (!empty($arrayData['choices'][0]['finish_reason'])) {
                            $server->push($fd, '{"type":"message","content":"","status":true}');
                        } elseif (isset($arrayData['error'])) {
                            $server->push($fd, '{"type":"error","content":"' . ($arrayData['error']['message'] ?? '') . '","status":true}');
                        }
                    }
                }
                return \strlen($data);
            });

            $date = date('Y-m-d H:i:s', $time);
            $this->chatMessageService->save([
                'role'             => 2,
                'sid'              => $data->sid ?: 1,
                'content'          => htmlspecialchars($data->content),
                'reply_content'    => $replyContent  ? htmlspecialchars($replyContent) : '',
                'reply_at'         => $date,
                'created_at'       => $date,
            ]);

            $server->push($fd, '{"type":"message","content":"","status":true}');
        }catch (\Throwable $exception){
            $server->push($fd, '{"type":"error","content":"'.$exception->getMessage().'","status":true}');
        }
    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        try {
            // 解除绑定fd
            $user = $this->redis->hGetAll(RedisConst::FD_TO_USER . $fd);
            if (!empty($user['uid'])) {
                $this->redis->zRem(RedisConst::USER_TO_FD, $user['uid']);
            }
            $this->redis->del(RedisConst::FD_TO_USER . $fd);
        }catch (\Throwable $exception){

        }
    }

    /**
     * @param Response|Server $server
     * @param Request $request
     * @throws \RedisException
     */
    public function onOpen($server, $request): void
    {
        try {

            $token = $request->get['token'] ?? '';
            if (empty($token)){
                $server->push($request->fd, '{"type":"login","content":"token无效1"}');
                $server->close();
                return;
            }

            if (false === $this->loginService->check($token, 'ai')) {
                $server->push($request->fd, '{"type":"login","content":"token无效2"}');
                $server->close();
                return;
            }

            $userInfo = $this->loginService->getInfo($token);
            if (empty($userInfo['id'])){
                $server->push($request->fd, '{"type":"login","content":"token无效3"}');
                $server->close();
                return;
            }

            $this->redis->zAdd(RedisConst::USER_TO_FD, $request->fd, $userInfo['id']);
            $this->redis->hMSet(RedisConst::FD_TO_USER . $request->fd, [
                'uid' => $userInfo['id'],
                'vip' => $userInfo['vip'] ?? 0,
            ]);
            $this->redis->expire(RedisConst::USER_TO_FD, 86400);
            $this->redis->expire(RedisConst::FD_TO_USER . $request->fd, 86400);
            $server->push($request->fd, '{"type":"opened","content":"ok"}');
        } catch (\Throwable $exception) {
            $server->push($request->fd, '{"type":"error","content":"' . $exception->getMessage() . '"}');
        }
    }
}