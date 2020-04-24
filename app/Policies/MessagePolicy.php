<?php

namespace App\Policies;

use App\User;
use App\Message;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class MessagePolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
      // Any user can create a new message
      return Auth::check();
    }

    public function delete(User $user, Message $message)
    {
      // Only a message sender can delete it
      return $user->user_id == $message->sender_id;
    }
}