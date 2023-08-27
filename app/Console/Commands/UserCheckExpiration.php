<?php

namespace App\Console\Commands;

use App\Mail\DeleteInGroupMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserCheckExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check_expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = <<<'DESCRIPTION'
    Данная команда: Исключается пользователей из групп, у которых expired_at меньше текущего момента времен; Отправляет
    сообщения тем кому удалили но email; По факту исключения пользователя из группы поставить в очередь задачу:
    если пользователь не входит ни в одну группу,
    деактивировать его (установить у пользователя active = false).
    DESCRIPTION;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $groupMembershipForDeleteArray  = DB::table('group_user')
            ->select('users.name as userName', 'groups.name as groupName', 'users.email as email', 'users.id as userId')
            ->join('users', 'users.id', '=', 'group_user.user_id')
            ->join('groups', 'group_user.group_id', '=', 'groups.id')
            ->where('group_user.expired_at', '<', Date::now())
            ->get()
            ->toArray();

        if (count($groupMembershipForDeleteArray) === 0) {
            $this->info('Нет пользователей которых нужно исключить из группы');
            return;
        }

        DB::table('group_user')
            ->where('expired_at', '<', Date::now())->delete();

        foreach ($groupMembershipForDeleteArray as $memberShip) {
            var_dump($memberShip->email);
            Mail::to($memberShip->email)->send(
                new DeleteInGroupMail($memberShip->userName, $memberShip->groupName)
            );
        }

        $this->info('Пользователи успешно удалены их групп');
    }
}
