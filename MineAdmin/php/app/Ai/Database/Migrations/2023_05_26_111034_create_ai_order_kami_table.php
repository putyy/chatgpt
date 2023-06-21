<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiOrderKamiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_order_kami', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('订单关联卡密');
            $table->integer('oid')->primary()->comment('订单ID');
            $table->integer('kid')->comment('卡密ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_order_kami');
    }
}
