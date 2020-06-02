<?php

namespace App\Events;


use App\Notification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;


class NewNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public $image;
    public $dest;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Notification $notification, $image, $dest)
    {
        $this->notification = $notification;
        $this->image = $image;
        $this->dest = $dest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {   
        if($this->notification->notification_event_id)
            return new Channel('notifiedEvent.'.$this->notification->notification_event_id);
        else if($this->notification->notification_group_id)
            return new Channel('notifiedGroup.'.$this->notification->notification_group_id);
        else
            return new Channel('notifiedUser.'.$this->dest);
    }

}
