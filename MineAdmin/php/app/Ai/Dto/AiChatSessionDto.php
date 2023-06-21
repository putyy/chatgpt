<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 问答会话Dto （导入导出）
 */
#[ExcelData]
class AiChatSessionDto implements MineModelExcel
{
    #[ExcelProperty(value: "主键", index: 0)]
    public string $id;

    #[ExcelProperty(value: "用户uid", index: 1)]
    public string $uid;

    #[ExcelProperty(value: "模型ID", index: 2)]
    public string $prompt_id;

    #[ExcelProperty(value: "是否关闭:1正常,2关闭", index: 3)]
    public string $close;

    #[ExcelProperty(value: "是否分享:1关闭,2公开", index: 4)]
    public string $share;

    #[ExcelProperty(value: "创建时间", index: 5)]
    public string $created_at;


}