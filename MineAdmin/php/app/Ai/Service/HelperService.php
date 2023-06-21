<?php
declare(strict_types=1);

namespace App\Ai\Service;

use App\Ai\Middleware\AuthMiddleware;
use Hyperf\Context\Context;

class HelperService
{
    public static function buildSavePath(string $url, $needle = 'upload'): string
    {
        if (empty($url)) {
            return '';
        }
        $pathInfo = parse_url($url);
        $start = strpos($pathInfo['path'], $needle);
        $str = '';
        if ($start !== false) {
            $str = substr($pathInfo['path'], $start);
        }

        parse_str($pathInfo['query'] ?? '', $query_arr);
        return isset($query_arr['v']) ? $str . '?v=' . $query_arr['v'] : $str;
    }

    public static function makeFileName(string $scene, string $suffix, int $uid = 0): string
    {
        if ($uid > 0) {
            // 用户唯一
            $filename = 'upload/' . $scene . '/' . md5(config('app_name') . '-' . $scene . '-' . $uid);
        } else {
            $filename = 'upload/' . $scene . '/' . date('Ymd') . uniqid() . mt_rand(100000000, 999999999);
        }

        return $filename . $suffix;
    }

    public static function buildSourceUrl(?string $filename): string
    {
        if (empty($filename)) {
            return '';
        }
        if (strpos($filename, 'http') === 0 || strpos($filename, '//') === 0) {
            return $filename;
        }
        // upload/1000/a.png
        $type = (int)substr($filename, 7, 1) - 1;
        $config = config('file.storage.qiniu');
        $domain = [$config['image_domain'], $config['audio_domain'], $config['video_domain']];
        $schema = self::isMini() ? 'https' : 'http';
        switch ($type) {
            case 2:
                return $schema . '://' . $domain[$type - 1] . '/' . config('app_env') . '/' . $filename;
            case 1:
            default:
                return $schema . '://' . ($domain[$type - 1] ?? $domain[0]) . '/' . config('app_env') . '/' . $filename;
        }
    }

    /**
     * 是否小程序请求
     * @return bool
     */
    public static function isMini(): bool
    {
        return isset(Context::get(AuthMiddleware::class . '_login_data')['mini_openid']);
    }

    public static function decode100(string|int $price, string $percent = '0.01') : string
    {
        return bcmul((string)$price, $percent, 2);
    }

    public static function encode100(string|int|float $price, string $hundred = '100') : string
    {
        return bcmul((string)$price, $hundred, 0);
    }

    public static function createOrderCode(string|int $uid=0) : string
    {
        mt_srand();
        return date("YmdHis") . $uid . rand(100000, 999999);
    }

    public static function randStrArr(int $len = 6, int $number = 1): array
    {
        mt_srand();
        $res = [];
        $str = 'a b c d e f g h i j k l m n o p q r s t u v w x y z A B C D E F G H I J K L M N O P Q R S T U V W X Y Z 0 1 2 3 4 5 6 7 8 9';
        $arr = explode(' ', $str);
        do {
            $rand_keys = array_rand($arr, $len);
            shuffle($rand_keys);
            $code = '';
            foreach ($rand_keys as $index => $key) {
                $code .= $arr[$key];
            }
            array_push($res, $code);
            --$number;
        } while ($number);
        return $res;
    }
}