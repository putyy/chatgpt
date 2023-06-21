<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 图片素材Dto （导入导出）
 */
#[ExcelData]
class AiImageMaterialDto implements MineModelExcel
{
    #[ExcelProperty(value: "主键", index: 0)]
    public string $id;

    #[ExcelProperty(value: "使用场景", index: 1)]
    public string $scene;

    #[ExcelProperty(value: "图片地址", index: 2)]
    public string $img_url;

    #[ExcelProperty(value: "跳转地址", index: 3)]
    public string $url;

    #[ExcelProperty(value: "备注", index: 4)]
    public string $remark;

    #[ExcelProperty(value: "排序", index: 5)]
    public string $sort;

    #[ExcelProperty(value: "创建者", index: 6)]
    public string $created_by;

    #[ExcelProperty(value: "更新者", index: 7)]
    public string $updated_by;

    #[ExcelProperty(value: "使用开始时间", index: 8)]
    public string $start_at;

    #[ExcelProperty(value: "使用结束时间", index: 9)]
    public string $end_at;

    #[ExcelProperty(value: "创建时间", index: 10)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 11)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 12)]
    public string $deleted_at;


}