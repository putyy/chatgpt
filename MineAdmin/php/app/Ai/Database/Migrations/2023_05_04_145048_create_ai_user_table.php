<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_user', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('用户主表');
            $table->increments('id')->comment('主键');
            $table->addColumn('string', 'nick_name', ['length' => 50, 'comment' => '昵称'])->default('')->nullable(false);
            $table->addColumn('string', 'head_img', ['length' => 100, 'comment' => '头像'])->default('')->nullable(false);
            $table->addColumn('string', 'mobile', ['length' => 20, 'comment' => '手机号'])->default('')->nullable(false);
            $table->addColumn('tinyInteger', 'vip', ['length' => 1, 'comment' => 'vip等级'])->index()->default(0)->nullable(false);
            $table->addColumn('timestamp', 'vip_ent_at', ['precision' => 0, 'comment' => 'vip到期时间'])->nullable();
            $table->addColumn('tinyInteger', 'is_lock', ['length' => 1, 'comment' => '是否锁定:1正常,2锁定'])->default(1)->nullable(false);
            $table->addColumn('string', 'password', ['length' => 32, 'comment' => '密码'])->default('')->nullable(false);
            $table->addColumn('integer', 'updated_by', ['comment' => '更新者'])->default(0)->nullable();
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
        Schema::dropIfExists('ai_user');
    }
}
