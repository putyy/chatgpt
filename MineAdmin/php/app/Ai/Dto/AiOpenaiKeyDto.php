<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * openai_keyDto （导入导出）
 */
#[ExcelData]
class AiOpenaiKeyDto implements MineModelExcel
{
    #[ExcelProperty(value: "openai_key", index: 0)]
    public string $openai_key;

    #[ExcelProperty(value: "备注", index: 1)]
    public string $remark;

    #[ExcelProperty(value: "主键", index: 2)]
    public string $id;

    #[ExcelProperty(value: "创建者", index: 3)]
    public string $created_by;

    #[ExcelProperty(value: "更新者", index: 4)]
    public string $updated_by;

    #[ExcelProperty(value: "创建时间", index: 5)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 6)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 7)]
    public string $deleted_at;


}