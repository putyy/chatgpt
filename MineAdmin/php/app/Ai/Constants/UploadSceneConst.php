<?php
namespace App\Ai\Constants;

class UploadSceneConst
{
    // 1开头，例如 1001 1002
    const ImageScene = [
        'ai_head_img'          => '1010',
        'ai_mine_menu_icon'    => '1011',
        'ai_customer_wx_img'   => '1012',
        'ai_customer_head_img' => '1013',
        'ai_image_materialg'   => '1014',
    ];

    // 2开头，例如 2001 2002
    const AudioScene = [
    ];

    // 3开头，例如 3001 3002
    const VideoScene = [
    ];

    public static function hasScene(string $scene): bool
    {
        if (in_array($scene, self::ImageScene) || in_array($scene, self::AudioScene) || in_array($scene, self::VideoScene)) {
            return true;
        }
        return false;
    }

    public static function isOnly(string $scene): bool
    {
        return in_array($scene, ['1010']);
    }
}