<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentDeleted implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $parent;


    /**
     * @var User
     */
    public $user;


    /**
     * Create a new event instance.
     *
     * @param int  $id
     * @param int  $parent
     * @param User $user
     */
    public function __construct($id, $parent, User $user)
    {
        $this->id = $id;
        $this->parent = $parent;
        $this->user = $user;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('boss-river-135');
    }
}
