<?php

namespace App\Policies;

use App\User;
use App\RegularUser;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class EventPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        if (!Auth::check())
            return false;

        if(!$user->userable_type == 'App\RegularUser')
            return false;

        $regular_user = $user->userable;

        return $regular_user->regular_userable_type == 'App\Organization';
    }

    /*public function delete(User $user, Post $post)
    {
        // Only a post owner can delete it
        return $user->userable_id == $post->author_id;
    }

    public function edit(User $user, Post $post)
    {
        // Only a post owner can edit it
        return $user->userable_id == $post->author_id;
    }*/
}
