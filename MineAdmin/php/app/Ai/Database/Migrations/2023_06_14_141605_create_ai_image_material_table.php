<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiImageMaterialTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_image_material', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('图片素材');
            $table->increments('id')->comment('主键');
            $table->addColumn('tinyInteger', 'scene', ['length' => 1, 'comment' => '使用场景'])->default(1)->nullable(false);
            $table->addColumn('string', 'img_url', ['length' => 255, 'comment' => '图片地址'])->default('')->nullable(false);
            $table->addColumn('string', 'url', ['length' => 255, 'comment' => '跳转地址'])->default('')->nullable(false);
            $table->addColumn('string', 'remark', ['length' => 255, 'comment' => '备注'])->default('')->nullable(false);
            $table->addColumn('integer', 'sort', ['comment' => '排序'])->default(0)->nullable(false);
            $table->addColumn('integer', 'created_by', ['comment' => '创建者'])->default(0)->nullable(false);
            $table->addColumn('integer', 'updated_by', ['comment' => '更新者'])->default(0)->nullable(false);
            $table->addColumn('timestamp', 'start_at', ['precision' => 0, 'comment' => '使用开始时间'])->nullable();
            $table->addColumn('timestamp', 'end_at', ['precision' => 0, 'comment' => '使用结束时间'])->nullable();
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
        Schema::dropIfExists('ai_image_material');
    }
}
