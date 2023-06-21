<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiOrderVipTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_order_vip', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('vip订单');
            $table->integer('oid')->primary()->comment('订单ID');
            $table->addColumn('tinyInteger', 'vip_level', ['length'=>1, 'comment' => 'vip等级'])->default(1)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_order_vip');
    }
}
