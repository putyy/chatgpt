<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 用户主表Dto （导入导出）
 */
#[ExcelData]
class AiUserDto implements MineModelExcel
{
    #[ExcelProperty(value: "主键", index: 0)]
    public string $id;

    #[ExcelProperty(value: "昵称", index: 1)]
    public string $nick_name;

    #[ExcelProperty(value: "头像", index: 2)]
    public string $head_img;

    #[ExcelProperty(value: "手机号", index: 3)]
    public string $mobile;

    #[ExcelProperty(value: "vip等级", index: 4)]
    public string $vip;

    #[ExcelProperty(value: "vip到期时间", index: 5)]
    public string $vip_ent_at;

    #[ExcelProperty(value: "password", index: 6)]
    public string $password;

    #[ExcelProperty(value: "是否锁定:1正常,2锁定", index: 7)]
    public string $is_lock;

    #[ExcelProperty(value: "更新者", index: 8)]
    public string $updated_by;

    #[ExcelProperty(value: "创建时间", index: 9)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 10)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 11)]
    public string $deleted_at;


}