<?php

namespace App\Events;

use App\Models\Group;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExpiredAtEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $userToUpdate;

    public Group $groupToUpdate;

    /**
     * @param User $user модель юзер
     *
     */
    public function __construct(User $user, Group $group)
    {
        $this->userToUpdate = $user;
        $this->groupToUpdate = $group;
    }
}
