<?php
declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class AiMineMenuGroup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::table('ai_mine_menu_group')->truncate();
        $tableName = env('DB_PREFIX') . \App\Ai\Model\AiMineMenuGroup::getModel()->getTable();
        Db::insert("INSERT INTO `$tableName`(`id`, `name`, `sort`, `is_lock`, `created_at`, `updated_at`) VALUES (1, '会员服务', 1, 1, now(), now());");
        echo '大功告成'.PHP_EOL;
    }
}
