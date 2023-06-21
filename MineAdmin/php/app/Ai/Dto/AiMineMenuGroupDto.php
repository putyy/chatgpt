<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 个人中心菜单分组Dto （导入导出）
 */
#[ExcelData]
class AiMineMenuGroupDto implements MineModelExcel
{
    #[ExcelProperty(value: "主键", index: 0)]
    public string $id;

    #[ExcelProperty(value: "分组名称", index: 1)]
    public string $name;

    #[ExcelProperty(value: "排序", index: 2)]
    public string $sort;

    #[ExcelProperty(value: "是否锁定:1正常,锁定", index: 3)]
    public string $is_lock;

    #[ExcelProperty(value: "创建时间", index: 4)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 5)]
    public string $updated_at;


}