<?php


use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAiUserWalletTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_user_wallet', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('用户钱包');
            $table->addColumn('integer', 'uid')->primary()->comment('用户UID');
            $table->addColumn('integer', 'balance')->comment('余额')->unsigned()->default(0)->nullable(false);
            $table->addColumn('integer', 'balance_total')->comment('总收入')->unsigned()->default(0)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_user_wallet');
    }
}
