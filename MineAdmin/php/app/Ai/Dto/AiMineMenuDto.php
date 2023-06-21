<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 个人中心菜单Dto （导入导出）
 */
#[ExcelData]
class AiMineMenuDto implements MineModelExcel
{
    #[ExcelProperty(value: "主键", index: 0)]
    public string $id;

    #[ExcelProperty(value: "分组ID", index: 1)]
    public string $gid;

    #[ExcelProperty(value: "分组名称", index: 2)]
    public string $name;

    #[ExcelProperty(value: "排序", index: 3)]
    public string $sort;

    #[ExcelProperty(value: "使用权限限制 0全部", index: 4)]
    public string $use_vip;

    #[ExcelProperty(value: "点击类型 1跳转 2调用函数", index: 5)]
    public string $click_type;

    #[ExcelProperty(value: "函数标识 小程序端提前封装", index: 6)]
    public string $click_func;

    #[ExcelProperty(value: "打开的页面路径", index: 7)]
    public string $path;

    #[ExcelProperty(value: "小程序appid", index: 8)]
    public string $app_id;

    #[ExcelProperty(value: "需要传递给目标小程序的数据 json", index: 9)]
    public string $extra_data;

    #[ExcelProperty(value: "要打开的小程序版本", index: 10)]
    public string $env_version;

    #[ExcelProperty(value: "小程序链接", index: 11)]
    public string $short_link;

    #[ExcelProperty(value: "icon", index: 12)]
    public string $icon;

    #[ExcelProperty(value: "是否锁定:1正常,2锁定", index: 13)]
    public string $is_lock;

    #[ExcelProperty(value: "创建时间", index: 14)]
    public string $created_at;

    #[ExcelProperty(value: "更新时间", index: 15)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 16)]
    public string $deleted_at;


}