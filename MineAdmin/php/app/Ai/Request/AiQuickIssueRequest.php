<?php
declare(strict_types=1);

namespace App\Ai\Request;

use Mine\MineFormRequest;

/**
 * 快捷问题
验证数据类
 */
class AiQuickIssueRequest extends MineFormRequest
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
            //问题标题 验证
            'title' => 'required',
            //问题描述 验证
            'content' => 'required',
            // 验证
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
            //问题标题 验证
            'title' => 'required',
            //问题描述 验证
            'content' => 'required',
            // 验证
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
            'title' => '问题标题',
            'content' => '问题描述',
            'sort' => '',

        ];
    }

}