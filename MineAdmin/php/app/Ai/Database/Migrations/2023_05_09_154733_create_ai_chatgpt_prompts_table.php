<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiChatgptPromptsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_chatgpt_prompts', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('chatgpt角色');
            $table->increments('id')->comment('主键');
            $table->addColumn('string', 'act', ['length'=>100, 'comment' => '角色名称'])->default('')->nullable(false);
            $table->addColumn('text', 'prompt', ['comment' => '角色说明'])->nullable(false);
            $table->addColumn('integer', 'sort', ['comment' => '排序'])->default(0)->nullable(false);
            $table->addColumn('string', 'remark', ['length' => 255, 'comment' => '备注'])->default('')->nullable(false);
            $table->addColumn('integer', 'created_by', ['comment' => '创建者'])->default(0)->nullable(false);
            $table->addColumn('integer', 'updated_by', ['comment' => '更新者'])->default(0)->nullable(false);
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
        Schema::dropIfExists('ai_chatgpt_prompts');
    }
}
