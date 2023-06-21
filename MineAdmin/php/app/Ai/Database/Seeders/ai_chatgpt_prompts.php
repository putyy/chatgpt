<?php
declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class AiChatgptPrompts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $isInit = \App\Ai\Model\AiChatgptPrompts::where(['id'=>1])->first();
        if (!empty($isInit)){
            echo '大功告成'.PHP_EOL;
            return;
        }

        $tableName = env('DB_PREFIX') . \App\Ai\Model\AiChatgptPrompts::getModel()->getTable();
        $sql = [
            "INSERT INTO `{$tableName}`(`id`, `act`, `prompt`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (1, 'Ai助手', '你是一个Ai智能助手，我需要你模拟一名温柔贴心的女朋友来回答我的问题。', 0, 1, 1, now(), now(), NULL, '')",
        ];
        foreach ($sql as $item) {
            Db::insert($item);
        }
        echo '大功告成'.PHP_EOL;
    }
}
