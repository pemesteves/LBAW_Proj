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
    public function edit(User $user, Group $group)
    {
        if(!$user->userable_type == 'App\RegularUser')
            return false;

        $user_in_group = DB::table('user_in_group')
            ->where('user_id', '=', $user->userable_id)
            ->where('group_id', '=', $group->group_id)
            ->get();

        if(!isset($user_in_group))
            return false;
        
        return $user_in_group[0]->admin;
    }

    public function show(User $user, Group $group){
        return Auth::user()->userable->groups->find($group) != null;
    }
}
