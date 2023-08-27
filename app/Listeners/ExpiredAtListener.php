<?php

namespace App\Listeners;

use App\Events\ExpiredAtEvent;
use Illuminate\Support\Facades\Date;

class ExpiredAtListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ExpiredAtEvent $event): void
    {
        $user = $event->userToUpdate;
        $groupId = $event->groupToUpdate->id;
        $expireHours = $event->groupToUpdate->expire_hours;

        $data = ['expired_at' => Date::now()->addHours($expireHours)];

        $user->groups()->sync([$groupId => $data], false);
    }
}
