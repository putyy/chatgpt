<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiUserRelationTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_user_relation', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('用户关系');
            $table->addColumn('integer', 'uid')->primary()->comment('用户UID');
            $table->addColumn('integer', 'from_uid', ['comment' => '上级'])->index()->default(1)->nullable(false);
            $table->addColumn('integer', 'market_uid', ['comment' => '上级营销部'])->index()->default(0)->nullable(false);
            $table->addColumn('timestamp', 'created_at', ['precision' => 0, 'comment' => '创建时间'])->nullable();
            $table->addColumn('timestamp', 'updated_at', ['precision' => 0, 'comment' => '更新时间'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_user_relation');
    }
}
