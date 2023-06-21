<?php
namespace App\Ai\Dto;

use Mine\Interfaces\MineModelExcel;
use Mine\Annotation\ExcelData;
use Mine\Annotation\ExcelProperty;

/**
 * 订单表Dto （导入导出）
 */
#[ExcelData]
class AiOrderDto implements MineModelExcel
{
    #[ExcelProperty(value: "主键", index: 0)]
    public string $id;

    #[ExcelProperty(value: "用户UID", index: 1)]
    public string $uid;

    #[ExcelProperty(value: "上级UID", index: 2)]
    public string $from_uid;

    #[ExcelProperty(value: "营销部UID", index: 3)]
    public string $market_uid;

    #[ExcelProperty(value: "订单号", index: 4)]
    public string $ord_sn;

    #[ExcelProperty(value: "订单类型: 1开通VIP 2提现 3成为营销部", index: 5)]
    public string $ord_type;

    #[ExcelProperty(value: "支付方式: 1微信 2后台付费", index: 6)]
    public string $pay_type;

    #[ExcelProperty(value: "订单状态 1未支付(待处理) 2已支付(已完成) 3失败", index: 7)]
    public string $status;

    #[ExcelProperty(value: "总金额", index: 8)]
    public string $total_price;

    #[ExcelProperty(value: "实际金额", index: 9)]
    public string $amount_price;

    #[ExcelProperty(value: "订单描述", index: 10)]
    public string $content;

    #[ExcelProperty(value: "备注", index: 11)]
    public string $remark;

    #[ExcelProperty(value: "创建者", index: 12)]
    public string $created_by;

    #[ExcelProperty(value: "更新者", index: 13)]
    public string $updated_by;

    #[ExcelProperty(value: "创建时间", index: 14)]
    public string $created_at;

    #[ExcelProperty(value: "订单完成时间", index: 15)]
    public string $pay_at;

    #[ExcelProperty(value: "更新时间", index: 16)]
    public string $updated_at;

    #[ExcelProperty(value: "删除时间", index: 17)]
    public string $deleted_at;


}