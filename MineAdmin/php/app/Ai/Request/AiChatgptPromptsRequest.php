<?php
declare(strict_types=1);

namespace App\Ai\Request;

use Mine\MineFormRequest;

/**
 * chatgpt角色验证数据类
 */
class AiChatgptPromptsRequest extends MineFormRequest
{
    /**
     * 公共规则
     */
    public function commonRules(): array
    {
        return [];
    }

    
    /**
     * 新增数据验证规则
     * return array
     */
    public function saveRules(): array
    {
        return [
            //角色名称 验证
            'act' => 'required',
            //角色说明 验证
            'prompt' => 'required',
            //排序 验证
            'sort' => 'required',

        ];
    }
    /**
     * 更新数据验证规则
     * return array
     */
    public function updateRules(): array
    {
        return [
            //角色名称 验证
            'act' => 'required',
            //角色说明 验证
            'prompt' => 'required',
            //排序 验证
            'sort' => 'required',

        ];
    }

    
    /**
     * 字段映射名称
     * return array
     */
    public function attributes(): array
    {
        return [
            'id' => '主键',
            'act' => '角色名称',
            'prompt' => '角色说明',
            'sort' => '排序',

        ];
    }

}