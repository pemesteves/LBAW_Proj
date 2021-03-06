<?php

namespace App\Events;

use App\Chat;
use App\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;


class NewMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $image;
    public $user_name;
    public $id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message, $image, String $user_name, String $id)
    {
        $this->message = $message;
        $this->image = $image;
        $this->user_name = $user_name;
        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat.'.$this->message->chat_id);
    }

    // public function broadcastWith() {
    //     return [
    //         'id' => $this->message->message_id,
    //         'body' => $this->message->body,
    //         'date' => $this->message->date->toFormattedDateString(),
    //         'sender_id' => $this->message->sender_id
    //     ];
    // }
}
