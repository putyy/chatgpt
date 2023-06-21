<?php
declare(strict_types=1);

namespace App\Ai\Crontab;

use App\Ai\Model\AiUser;
use Mine\Annotation\Transaction;

class CheckVipOver
{
    #[Transaction]
    public function execute(): string
    {
        // todo
        try {
            $lastId = 0;
            $time = time();
            while (true){
                $userList = AiUser::where('id', '>', $lastId)->where('vip','>', 0)->limit(1000)->get();
                /**
                 * @var AiUser $user
                 */
                $uidArr = [];
                foreach ($userList as $user) {
                    if ($user->vip_ent_at && $time > strtotime($user->vip_ent_at)) {
                        $uidArr[] = $user->id;
                    }
                    $lastId = $user->id;
                }
                $uidArr && AiUser::whereIn('id', $uidArr)->update([
                    'vip' => 0
                ]);
                if (count($userList) < 1000) {
                    break;
                }
            }
        } catch (\Throwable $exception){

        }
        return 'success';
    }
}