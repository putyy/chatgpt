<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 聊天数据Dto （导入导出）
 */
#[ExcelData]
class AiChatMessageDto implements MineModelExcel
{
    #[ExcelProperty(value: "主键", index: 0)]
    public string $id;

    #[ExcelProperty(value: "会话ID", index: 1)]
    public string $sid;

    #[ExcelProperty(value: "内容", index: 2)]
    public string $content;

    #[ExcelProperty(value: "回复内容", index: 3)]
    public string $reply_content;

    #[ExcelProperty(value: "回复时间", index: 4)]
    public string $reply_at;

    #[ExcelProperty(value: "创建时间", index: 4)]
    public string $created_at;

    #[ExcelProperty(value: "删除时间", index: 5)]
    public string $deleted_at;


}