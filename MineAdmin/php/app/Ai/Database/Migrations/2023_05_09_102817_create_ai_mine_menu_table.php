<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiMineMenuTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_mine_menu', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('个人中心菜单');
            $table->bigIncrements('id')->comment('主键');
            $table->addColumn('integer', 'gid', ['comment' => '分组ID'])->index()->default(0)->nullable(false);
            $table->addColumn('string', 'name', ['length' => 50, 'comment' => '菜单名称'])->default('')->nullable(false);
            $table->addColumn('integer', 'sort', ['comment' => '排序'])->default(0)->nullable(false);
            $table->addColumn('tinyInteger', 'use_vip', ['length' => 2, 'comment' => '使用权限限制 0全部'])->default(0)->nullable(false);
            $table->addColumn('tinyInteger', 'click_type', ['length' => 1, 'comment' => '点击类型 1跳转 2调用函数'])->default(1)->nullable(false);
            $table->addColumn('string', 'click_func', ['length' => 50, 'comment' => '函数标识 小程序端提前封装'])->default('')->nullable(false);
            $table->addColumn('string', 'path', ['length' => 100, 'comment' => '打开的页面路径'])->default('')->nullable(false);
            $table->addColumn('string', 'app_id', ['length' => 100, 'comment' => '小程序appid'])->default('')->nullable(false);
            $table->addColumn('string', 'extra_data', ['length' => 100, 'comment' => '需要传递给目标小程序的数据 json'])->default('')->nullable(false);
            $table->addColumn('string', 'env_version', ['length' => 100, 'comment' => '要打开的小程序版本'])->default('')->nullable(false);
            $table->addColumn('string', 'short_link', ['length' => 100, 'comment' => '小程序链接'])->default('')->nullable(false);
            $table->addColumn('string', 'icon', ['length' => 255, 'comment' => '菜单图标'])->default('')->nullable(false);
            $table->addColumn('tinyInteger', 'is_lock', ['length' => 1, 'comment' => '是否锁定:1正常,2锁定'])->default(1)->nullable(false);
            $table->addColumn('timestamp', 'created_at', ['precision' => 0, 'comment' => '创建时间'])->nullable();
            $table->addColumn('timestamp', 'updated_at', ['precision' => 0, 'comment' => '更新时间'])->nullable();
            $table->addColumn('timestamp', 'deleted_at', ['precision' => 0, 'comment' => '删除时间'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_mine_menu');
    }
}
