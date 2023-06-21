<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiChatMessageTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_chat_message', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('聊天数据');
            $table->bigIncrements('id')->comment('主键');
            $table->addColumn('integer', 'sid', ['comment' => '会话ID'])->index()->default(0)->nullable(false);
            $table->addColumn('text', 'content', ['comment' => '内容'])->nullable(false);
            $table->addColumn('text', 'reply_content', ['comment' => '回复内容'])->nullable(false);
            $table->addColumn('timestamp', 'reply_at', ['precision' => 0, 'comment' => '回复时间'])->nullable();
            $table->addColumn('timestamp', 'created_at', ['precision' => 0, 'comment' => '创建时间'])->nullable();
            $table->addColumn('timestamp', 'deleted_at', ['precision' => 0, 'comment' => '删除时间'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chat_message');
    }
}
