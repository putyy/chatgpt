<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiPayKamiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_pay_kami', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('卡密');
            $table->increments('id')->comment('主键');
            $table->addColumn('integer', 'uid', ['comment' => '绑定用户'])->default(0)->nullable(false);
            $table->addColumn('integer', 'price', ['comment' => '价格'])->default(0)->nullable(false);
            $table->addColumn('string', 'code', ['length' => 32, 'comment' => '卡密号'])->unique()->default('')->nullable(false);
            $table->addColumn('tinyInteger', 'status', ['length' => 1, 'comment' => '状态 1未使用 2已使用'])->default(1)->nullable(false);
            $table->addColumn('string', 'remark', ['length' => 255, 'comment' => '备注'])->default('')->nullable(false);
            $table->addColumn('bigInteger', 'created_by', ['comment' => '创建者'])->default(0)->nullable(false);
            $table->addColumn('bigInteger', 'updated_by', ['comment' => '更新者'])->default(0)->nullable(false);
            $table->addColumn('timestamp', 'created_at', ['precision' => 0, 'comment' => '创建时间'])->nullable();
            $table->addColumn('timestamp', 'use_at', ['precision' => 0, 'comment' => '使用时间'])->nullable();
            $table->addColumn('timestamp', 'updated_at', ['precision' => 0, 'comment' => '更新时间'])->nullable();
            $table->addColumn('timestamp', 'deleted_at', ['precision' => 0, 'comment' => '删除时间'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_pay_carmi');
    }
}
