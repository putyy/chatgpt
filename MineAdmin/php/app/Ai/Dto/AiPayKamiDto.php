<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 卡密Dto （导入导出）
 */
#[ExcelData]
class AiPayKamiDto implements MineModelExcel
{
    #[ExcelProperty(value: "主键", index: 0)]
    public string $id;

    #[ExcelProperty(value: "绑定用户", index: 1)]
    public string $uid;

    #[ExcelProperty(value: "价格", index: 2)]
    public string $price;

    #[ExcelProperty(value: "卡密号", index: 3)]
    public string $code;

    #[ExcelProperty(value: "状态 1未使用 2已使用", index: 4)]
    public string $status;

    #[ExcelProperty(value: "备注", index: 5)]
    public string $remark;

    #[ExcelProperty(value: "创建者", index: 6)]
    public string $created_by;

    #[ExcelProperty(value: "更新者", index: 7)]
    public string $updated_by;

    #[ExcelProperty(value: "创建时间", index: 8)]
    public string $created_at;

    #[ExcelProperty(value: "使用时间", index: 9)]
    public string $use_at;

    #[ExcelProperty(value: "更新时间", index: 10)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 11)]
    public string $deleted_at;


}