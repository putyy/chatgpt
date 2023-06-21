<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiQuickIssueTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_quick_issue', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('快捷问题');
            $table->increments('id')->comment('主键');
            $table->addColumn('string', 'title', ['length' => 100, 'comment' => '问题标题'])->default('')->nullable(false);
            $table->addColumn('string', 'content', ['length' => 255, 'comment' => '问题描述'])->default('')->nullable(false);
            $table->addColumn('integer', 'sort', ['comment' => '排序'])->default(0)->nullable(false);
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
        Schema::dropIfExists('ai_quick_issue');
    }
}
