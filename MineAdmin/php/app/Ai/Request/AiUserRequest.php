<?php
declare(strict_types=1);

namespace App\Ai\Request;

use Mine\MineFormRequest;

/**
 * 用户主表验证数据类
 */
class AiUserRequest extends MineFormRequest
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

        ];
    }
    /**
     * 更新数据验证规则
     * return array
     */
    public function updateRules(): array
    {
        return [
            //昵称 验证
            'nick_name' => 'required',
            //头像 验证
            'head_img' => 'required',
            //手机号 验证
            'mobile' => 'required',
            //vip等级 验证
            'vip' => 'required',

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
            'nick_name' => '昵称',
            'head_img' => '头像',
            'mobile' => '手机号',
            'vip' => 'vip等级'
        ];
    }

}