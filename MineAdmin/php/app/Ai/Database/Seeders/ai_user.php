<?php
/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://gitee.com/xmo/MineAdmin
 */

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class AiUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $isInit = \App\Ai\Model\AiUser::where(['id'=>1])->first();
        if (!empty($isInit)){
            echo '大功告成'.PHP_EOL;
            return;
        }

        $tableName = env('DB_PREFIX') . \App\Ai\Model\AiUser::getModel()->getTable();

        Db::insert("INSERT INTO `{$tableName}`(`id`, `nick_name`, `head_img`, `mobile`, `vip`, `vip_ent_at`, `password`, `is_lock`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, 'ChatGPT', '', '123456789', 30, '2036-03-11 16:02:58', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, now(), now(), NULL);");

        \App\Ai\Model\AiUserRelation::insert([
            'uid'       => 1,
            'from_uid'  => 1,
        ]);

        \App\Ai\Model\AiUserWallet::insert([
            'uid' => 1,
        ]);
        echo '大功告成'.PHP_EOL;
    }
}
