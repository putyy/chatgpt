<?php
declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class AiMineMenu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::table('ai_mine_menu')->truncate();
        $tableName = env('DB_PREFIX') . \App\Ai\Model\AiMineMenu::getModel()->getTable();
        $sql = [
            "INSERT INTO `{$tableName}`(`id`, `gid`, `name`, `sort`, `use_vip`, `click_type`, `click_func`, `path`, `app_id`, `extra_data`, `env_version`, `short_link`, `icon`, `is_lock`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, 1, '会员特权', 4, 0, 2, '1002', '', '', '', '', '', 'https://www.putyy.com/uploads/images/vip.png', 1, now(), now(), NULL);",
            "INSERT INTO `{$tableName}`(`id`, `gid`, `name`, `sort`, `use_vip`, `click_type`, `click_func`, `path`, `app_id`, `extra_data`, `env_version`, `short_link`, `icon`, `is_lock`, `created_at`, `updated_at`, `deleted_at`) VALUES (2, 1, '联系客服', 3, 0, 2, '1001', '', '', '', '', '', 'https://www.putyy.com/uploads/images/kf.png', 2, now(), now(), NULL);",
            "INSERT INTO `{$tableName}`(`id`, `gid`, `name`, `sort`, `use_vip`, `click_type`, `click_func`, `path`, `app_id`, `extra_data`, `env_version`, `short_link`, `icon`, `is_lock`, `created_at`, `updated_at`, `deleted_at`) VALUES (3, 1, '清空缓存', 0, 0, 2, '1003', '', '', '', '', '', 'https://www.putyy.com/uploads/images/clear.png', 1, now(), now(), NULL);",
            "INSERT INTO `{$tableName}`(`id`, `gid`, `name`, `sort`, `use_vip`, `click_type`, `click_func`, `path`, `app_id`, `extra_data`, `env_version`, `short_link`, `icon`, `is_lock`, `created_at`, `updated_at`, `deleted_at`) VALUES (4, 1, '订单列表', 0, 0, 1, '', '/pages/user/order', '', '', '', '', 'https://www.putyy.com/uploads/images/list.png', 1, now(), now(), NULL);",
        ];
        foreach ($sql as $item){
            Db::insert($item);
        }
        echo '大功告成'.PHP_EOL;
    }
}
