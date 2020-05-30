<?php

namespace App\Policies;

use App\User;
use App\Event;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class EventPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        if (!Auth::check())
            return false;

        if($user->userable_type != 'App\RegularUser')
            return false;

        $regular_user = $user->userable;

        return $regular_user->regular_userable_type == 'App\Organization';
    }

    /*public function delete(User $user, Post $post)
    {
        // Only a post owner can delete it
        return $user->userable_id == $post->author_id;
    }
    */
    public function edit(User $user, Event $event)
    {
        if(!$user->userable_type == 'App\RegularUser')
            return false;

        $regular_user = $user->userable;

        if($regular_user->regular_userable_type != 'App\Organization')
            return false;
        
        $organization = $regular_user->regular_userable;

        
        return $organization->organization_id == $event->organization_id;
    }

    public function report(User $user)
    {
      // Any user can report a comment
      return Auth::check();
    }
}
