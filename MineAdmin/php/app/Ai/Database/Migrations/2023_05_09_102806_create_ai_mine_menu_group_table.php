<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiMineMenuGroupTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_mine_menu_group', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('个人中心菜单分组');
            $table->bigIncrements('id')->comment('主键');
            $table->addColumn('string', 'name', ['length' => 100, 'comment' => '分组名称'])->default('')->nullable(false);
            $table->addColumn('integer', 'sort', ['comment' => '排序'])->default(0)->nullable(false);
            $table->addColumn('tinyInteger', 'is_lock', ['length' => 1, 'comment' => '是否锁定:1正常,锁定'])->default(1)->nullable(false);
            $table->addColumn('timestamp', 'created_at', ['precision' => 0, 'comment' => '创建时间'])->nullable();
            $table->addColumn('timestamp', 'updated_at', ['precision' => 0, 'comment' => '更新时间'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_mine_menu_group');
    }
}
