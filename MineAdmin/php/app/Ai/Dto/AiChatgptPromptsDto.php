<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * chatgpt角色Dto （导入导出）
 */
#[ExcelData]
class AiChatgptPromptsDto implements MineModelExcel
{
    #[ExcelProperty(value: "主键", index: 0)]
    public string $id;

    #[ExcelProperty(value: "角色名称", index: 1)]
    public string $act;

    #[ExcelProperty(value: "角色说明", index: 2)]
    public string $prompt;

    #[ExcelProperty(value: "排序", index: 3)]
    public string $sort;

    #[ExcelProperty(value: "创建者", index: 4)]
    public string $created_by;

    #[ExcelProperty(value: "更新者", index: 5)]
    public string $updated_by;

    #[ExcelProperty(value: "创建时间", index: 6)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 7)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 8)]
    public string $deleted_at;

    #[ExcelProperty(value: "备注", index: 9)]
    public string $remark;


}