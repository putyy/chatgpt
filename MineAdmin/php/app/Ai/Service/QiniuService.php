<?php
declare(strict_types=1);

namespace App\Ai\Service;

use App\Ai\Constants\ResponseCodeConst;
use App\Ai\Middleware\AuthMiddleware;
use App\Ai\Constants\UploadSceneConst;
use EasySwoole\Oss\QiNiu\Auth;
use EasySwoole\Oss\QiNiu\Config;
use Hyperf\Context\Context;
use Mine\Exception\NormalStatusException;

class QiniuService
{
    public function __construct(){
        Config::setTimeout(3);
        Config::setConnectTimeout(5);
    }

    public function token(string $scenes): array
    {
        $sceneArr = explode('-', $scenes);
        $list = [];
        if (is_array($sceneArr) && array_filter($sceneArr)) {
            $config = config('file.storage.qiniu');
            $bucket = [$config['image_bucket'], $config['audio_bucket'], $config['video_bucket']];
            $domain = [$config['image_domain'], $config['audio_domain'], $config['video_domain']];
            // 小程序判断
            $schema = HelperService::isMini() ? 'https' : 'http';
            $suffix = ['.png', '.mp3', '.mp4'];
            $auth   = new Auth($config['accessKey'], $config['secretKey']);
            $time   = time();
            $env    = config('app_env');
            $uid    = Context::get(AuthMiddleware::class . '_login_data')['id'] ?? 0;
            foreach ($sceneArr as $key => $scene) {
                if (!UploadSceneConst::hasScene($scene)) {
                    throw new NormalStatusException('场景不存在~~', ResponseCodeConst::PARAM_FAILED);
                }
                $i                         = substr($scene, 0, 1) - 1;
                $list[$key]['path']        = $env . '/' . HelperService::makeFileName($scene, $suffix[$i] ?? $suffix[0], UploadSceneConst::isOnly($scene) ? $uid  : 0);
                $list[$key]['domain']      = $schema . '://' . ($domain[$i] ?? $domain[0]);
                $list[$key]['scene']       = $scene;

                /**
                 * https://developer.qiniu.com/kodo/1671/region-endpoint-fq
                 */
                $list[$key]['service_url'] = $config['upload_domain'];
                $returnBody                = '{"key":"$(key)","hash":"$(etag)","fsize":$(fsize),"bucket":"$(bucket)","name":"$(x:name)","url":"' . $list[$key]['domain'] . '/$(key)' . (UploadSceneConst::isOnly($scene) ? '?v=' . $time : '') . '"}';
                $list[$key]['token']       = $auth->uploadToken($bucket[$i] ?? $bucket[0], $list[$key]['path'], 900, ['returnBody' => $returnBody]);
            }
        }
        return $list;
    }
}