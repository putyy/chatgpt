<?php
declare(strict_types=1);

namespace App\Ai\Api;

use App\Ai\Middleware\AuthMiddleware;
use App\Ai\Service\AiChatgptPromptsService;
use App\Ai\Service\AiChatMessageService;
use App\Ai\Service\AiChatSessionService;
use App\Ai\Service\AiLoginService;
use App\Ai\Service\AiOpenaiKeyService;
use App\Ai\Service\AiQuickIssueService;
use Hyperf\Context\ApplicationContext;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Engine\Http\EventStream;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Orhanerday\OpenAi\OpenAi;
use Psr\Http\Message\ResponseInterface;

#[Controller(prefix: "ai/api/chat")]
#[Middleware(AuthMiddleware::class)]
class Chat extends BaseApi
{
    #[Inject]
    protected AiLoginService $loginService;

    #[Inject]
    protected AiChatgptPromptsService $chatgptPromptsService;

    #[Inject]
    protected AiChatSessionService $chatSessionService;

    #[Inject]
    protected AiChatMessageService $chatMessageService;

    #[Inject]
    protected AiQuickIssueService $quickIssueService;

    #[GetMapping("roles")]
    public function roles(): ResponseInterface
    {
        return $this->success([
            'list' => $this->chatgptPromptsService->getList([
                'select'    => 'id,act,prompt',
                'orderBy'   => 'sort',
                'orderType' => 'desc',
            ], false)
        ]);
    }

    #[GetMapping("model-list")]
    public function modelList(): ResponseInterface
    {
        return $this->success(AiChatgptPromptsService::MODEL_LIST);
    }

    #[GetMapping("session")]
    public function session(): ResponseInterface
    {
        return $this->success($this->chatSessionService->session($this->request->all()));
    }

    #[PostMapping("session-close")]
    public function sessionClose(): ResponseInterface
    {
        return $this->success([
            'sid' => $this->chatSessionService->sessionClose($this->request->all())
        ]);
    }

    #[GetMapping("messages")]
    public function messages(): ResponseInterface
    {
        // next、prev
        $sid     = $this->request->query('sid');
        $session = $this->chatSessionService->mapper->session([
            'id'  => $sid,
            'uid' => $this->loginService->getId()
        ]);
        if (empty($session)) {
            return $this->success([
                'list' => []
            ]);
        }
        return $this->success([
            'list' => $this->chatMessageService->messages($this->request->all())
        ]);
    }

    #[GetMapping("session-history")]
    public function sessionHistory(): ResponseInterface
    {
        return $this->success([
            'list' => $this->chatSessionService->sessionHistory($this->request->all())
        ]);
    }

    #[PostMapping("session-share")]
    public function sessionShare(): ResponseInterface
    {
        $model = $this->chatSessionService->mapper->session([
            'id'  => $this->request->post('id'),
            'uid' => $this->loginService->getId()
        ]);

        if (empty($model) || ($this->request->post('is_share', false) && $model->share === 2)) {
            return $this->success();
        }
        $model->share = $model->share === 1 ? 2 : 1;
        $model->save();
        return $this->success();
    }

    #[PostMapping("session-delete")]
    public function sessionDelete(): ResponseInterface
    {
        $model = $this->chatSessionService->mapper->session([
            'id'  => $this->request->post('id'),
            'uid' => $this->loginService->getId()
        ]);
        if (empty($model)) {
            return $this->success();
        }
        $model->delete();
        $this->chatMessageService->mapper->getModel()::where([
            'sid' => $this->request->post('id')
        ])->delete();
        return $this->success();
    }

    #[GetMapping("session-share-list")]
    public function sessionShareList(): ResponseInterface
    {
        return $this->success([
            'list' => $this->chatSessionService->sessionShareList($this->request->all())
        ]);
    }

    #[GetMapping("session-share-message-list")]
    public function sessionShareMessageList(): ResponseInterface
    {
        $sid     = (int)$this->request->query('sid');
        $session = $this->chatSessionService->read($sid);
        if (empty($session) || $session->share === 1) {
            return $this->success(['list' => []]);
        }
        return $this->success([
            'list' => $this->chatMessageService->shareMessageList($this->request->all())
        ]);
    }

    #[GetMapping("quick-issue")]
    public function quickIssue(): ResponseInterface
    {
        return $this->success([
            'list' => $this->quickIssueService->getList([
                'select'    => 'id,title,content',
                'orderBy'   => 'id',
                'orderType' => 'desc',
            ], false)
        ]);
    }

//    #[GetMapping("test")]
    public function test()
    {

        $token = $this->request->query('x-token', null);
        if ($token) {
            $token = 'Bearer ' . $token;
            //todo 验证
        }

        $opts = [
            'model'             => 'gpt-3.5-turbo',
            'messages'          => [],
            'temperature'       => 1.0,
            'max_tokens'        => 150,
            'frequency_penalty' => 0,
            'presence_penalty'  => 0,
            'stream'            => true,
        ];
        $opts['messages'][] = ['role' => 'system', 'content' => "你是一个AI助手，我需要你模拟一名温柔贴心的女朋友来回答我的问题。"];
        $opts['messages'][] = [
            'role'    => 'user',
            'content' => 'php如何计算1+1?',
        ];
        $response      = ApplicationContext::getContainer()->get(\Hyperf\HttpServer\Contract\ResponseInterface::class);
        $eventStream   = new EventStream($response->getConnection(), $response);
        $openAiService = ApplicationContext::getContainer()->get(AiOpenaiKeyService::class);
        $key           = $openAiService->openAiKey();
        $openAI        = new OpenAi($key);
        $baseUrl       = $openAiService->openaiProxy();
        $baseUrl && $openAI->setBaseURL($baseUrl);
        $openAI->setHeader(["Content-Type" => "text/event-stream"]);
        // 本次所有结果
        $replyContent = '';
        $openAI->chat($opts, function ($curl_info, $data) use ($eventStream, &$replyContent) {
            $datas = explode('data: ', $data);
            foreach ($datas as $dataStr) {
                $arrayData = json_decode($dataStr, true);
                if ($arrayData) {
                    if (isset($arrayData['choices'][0]['delta']['content'])) {
                        $replyContent .= ($content = $arrayData['choices'][0]['delta']['content'] ?? '');
                        $eventStream->write("data: " . json_encode([
                                'status' => 1,
                                'content' => $content,
                            ]) . PHP_EOL . PHP_EOL
                        );
                    } elseif (!empty($arrayData['choices'][0]['finish_reason'])) {
                        $eventStream->write("data: " . json_encode([
                                'status' => 2,
                                'content' => '',
                            ]) . PHP_EOL . PHP_EOL
                        );
                    } elseif (isset($arrayData['error'])) {
                        $eventStream->write("data: " . json_encode([
                                'status' => 0,
                                'content' => $arrayData['error']['message'],
                            ]) . PHP_EOL . PHP_EOL
                        );
                    }
                }
            }
            return \strlen($data);
        });
        return "data: " . PHP_EOL . PHP_EOL;
    }
}