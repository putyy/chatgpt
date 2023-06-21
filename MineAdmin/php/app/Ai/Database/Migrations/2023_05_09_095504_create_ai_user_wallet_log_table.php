<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiUserWalletLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_user_wallet_log', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('钱包变动记录');
            $table->increments('id')->comment('主键');
            $table->addColumn('integer', 'uid')->index()->comment('用户UID')->nullable(false);
            $table->addColumn('integer', 'oid')->comment('订单ID')->index()->default(0)->nullable(false);
            $table->addColumn('tinyInteger', 'direction', ['length' => 1, 'comment' => '类型:1收入,2支出'])->default(1)->nullable(false);
            $table->addColumn('integer', 'balance')->comment('金额')->default(0)->nullable(false);
            $table->addColumn('tinyInteger', 'scene', ['length' => 1, 'comment' => '变动场景: 1推广获益'])->default(1)->nullable(false);
            $table->addColumn('string', 'remark', ['length' => 255, 'comment' => '备注'])->default("")->nullable(false);
            $table->addColumn('timestamp', 'created_at', ['precision' => 0, 'comment' => '创建时间'])->nullable();
            $table->addColumn('timestamp', 'deleted_at', ['precision' => 0, 'comment' => '删除时间'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_user_wallet_log');
    }
}
