<?php

namespace App\Policies;

use App\User;
use App\Chat;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Chat $chat){
        return Auth::user()->userable->chats->find($chat) != null;
    }
}
