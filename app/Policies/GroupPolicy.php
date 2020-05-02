<?php

namespace App\Policies;

use App\User;
use App\Group;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return Auth::check();
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

    public function show(User $user, Group $group){
        return Auth::user()->userable->groups->find($group) != null;
    }
}
