<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiOrderTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_order', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('订单表');
            $table->increments('id')->comment('主键');
            $table->addColumn('integer', 'uid', ['comment' => '用户UID'])->index()->default(0)->nullable(false);
            $table->addColumn('integer', 'from_uid', ['comment' => '上级UID'])->default(0)->nullable(false);
            $table->addColumn('integer', 'market_uid', ['comment' => '营销部UID'])->default(0)->nullable(false);
            $table->addColumn('string', 'ord_sn', ['length' => 32, 'comment' => '订单号'])->unique()->default('')->nullable(false);
            $table->addColumn('tinyInteger', 'ord_type', ['length' => 1, 'comment' => '订单类型: 1开通VIP 2提现 3成为营销部'])->default(1)->nullable(false);
            $table->addColumn('tinyInteger', 'pay_type', ['length' => 1, 'comment' => '支付方式: 1微信 2后台付费'])->default(1)->nullable(false);
            $table->addColumn('tinyInteger', 'status', ['length' => 1, 'comment' => '订单状态 1未支付(待处理) 2已支付(已完成) 3失败'])->default(1)->nullable(false);
            $table->addColumn('integer', 'total_price', ['length' => 8, 'comment' => '总金额'])->default(0)->nullable(false);
            $table->addColumn('integer', 'amount_price', ['length' => 8, 'comment' => '实际金额'])->default(0)->nullable(false);
            $table->addColumn('string', 'content', ['length' => 255, 'comment' => '订单描述'])->default('')->nullable(false);
            $table->addColumn('string', 'remark', ['length' => 255, 'comment' => '备注'])->default("")->nullable(false);
            $table->addColumn('bigInteger', 'created_by', ['comment' => '创建者'])->default(0)->nullable();
            $table->addColumn('bigInteger', 'updated_by', ['comment' => '更新者'])->default(0)->nullable();
            $table->addColumn('timestamp', 'created_at', ['precision' => 0, 'comment' => '创建时间'])->nullable();
            $table->addColumn('timestamp', 'pay_at', ['precision' => 0, 'comment' => '订单完成时间'])->nullable();
            $table->addColumn('timestamp', 'updated_at', ['precision' => 0, 'comment' => '更新时间'])->nullable();
            $table->addColumn('timestamp', 'deleted_at', ['precision' => 0, 'comment' => '删除时间'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_order');
    }
}
