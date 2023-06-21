<?php
declare(strict_types=1);

namespace App\Ai\Request;

use Mine\MineFormRequest;

/**
 * 图片素材验证数据类
 */
class AiImageMaterialRequest extends MineFormRequest
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
            //使用场景 验证
            'scene' => 'required',
            //图片地址 验证
            'img_url' => 'required',
            //跳转地址 验证
            'url' => 'required',
            //备注 验证
            'remark' => 'required',
            //排序 验证
            'sort' => 'required',
            //使用开始时间 验证
            'start_at' => 'required',
            //使用结束时间 验证
            'end_at' => 'required',

        ];
    }
    /**
     * 更新数据验证规则
     * return array
     */
    public function updateRules(): array
    {
        return [
            //使用场景 验证
            'scene' => 'required',
            //图片地址 验证
            'img_url' => 'required',
            //跳转地址 验证
            'url' => 'required',
            //备注 验证
            'remark' => 'required',
            //排序 验证
            'sort' => 'required',
            //使用开始时间 验证
            'start_at' => 'required',
            //使用结束时间 验证
            'end_at' => 'required',

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
            'scene' => '使用场景',
            'img_url' => '图片地址',
            'url' => '跳转地址',
            'remark' => '备注',
            'sort' => '排序',
            'created_by' => '创建者',
            'updated_by' => '更新者',
            'start_at' => '使用开始时间',
            'end_at' => '使用结束时间',

        ];
    }

}