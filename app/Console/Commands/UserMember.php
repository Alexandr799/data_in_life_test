<?php

namespace App\Console\Commands;

use App\Events\ExpiredAtEvent;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class UserMember extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:member';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = <<<'DESCRIPTION'
    Добавить пользователя user_id в группу group_id,
    если пользователь не активен (active == false),
    активировать его (active = true)
    DESCRIPTION;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->ask('Введите id пользователя: ');

        $groupId = $this->ask('Введите id группы: ');

        $validator = Validator::make([
            'user_id' => $userId,
            'group_id' => $groupId
        ], [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'group_id' => ['required', 'integer', 'exists:groups,id'],
        ], [
            'user_id.required' => 'Поле id пользователя обязательно.',
            'user_id.integer' => 'Id пользователя должно быть целым числом.',
            'user_id.exists' => 'Пользователь с указанным id не найден.',
            'group_id.required' => 'Поле id группы обязательно.',
            'group_id.integer' => 'Id группы должно быть целым числом.',
            'group_id.exists' => 'Группа с указанным id не найдена.',
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return;
        }

        $user = User::where(['id' => $userId])->with('groups')->first();
        $group = Group::where(['id' => $groupId])->first();

        $data = ['expired_at' => Date::now()];

        $user->groups()->sync([$groupId => $data], false);

        $user->active = true;
        $user->save();

        event(new ExpiredAtEvent($user, $group));
        $this->info("Вы успешно добавили пользователя с id $userId в группу с id $groupId");
    }
}
