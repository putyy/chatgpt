<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiChatSessionTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_chat_session', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('问答会话');
            $table->increments('id')->comment('主键');
            $table->addColumn('integer', 'uid', ['comment' => '用户uid'])->index()->default(0)->nullable(false);
            $table->addColumn('integer', 'prompt_id', ['comment' => '模型ID'])->index()->default(0)->nullable(false);
            $table->addColumn('tinyInteger', 'close', ['length' => 1, 'comment' => '是否关闭:1正常,2关闭'])->default(1)->nullable(false);
            $table->addColumn('tinyInteger', 'share', ['length' => 1, 'comment' => '是否分享:1关闭,2公开'])->default(1)->nullable(false);
            $table->addColumn('timestamp', 'created_at', ['precision' => 0, 'comment' => '创建时间'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chat_session');
    }
}
