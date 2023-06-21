<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 快捷问题
Dto （导入导出）
 */
#[ExcelData]
class AiQuickIssueDto implements MineModelExcel
{
    #[ExcelProperty(value: "主键", index: 0)]
    public string $id;

    #[ExcelProperty(value: "问题标题", index: 1)]
    public string $title;

    #[ExcelProperty(value: "问题描述", index: 2)]
    public string $content;

    #[ExcelProperty(value: "sort", index: 3)]
    public string $sort;

    #[ExcelProperty(value: "创建时间", index: 4)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 5)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 6)]
    public string $deleted_at;


}