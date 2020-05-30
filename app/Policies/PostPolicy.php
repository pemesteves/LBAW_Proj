<?php

namespace App\Policies;

use App\User;
use App\Post;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PostPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
      // Any user can create a new post
      return Auth::check();
    }

    public function archive(User $user, Post $post)
    {
      // Only a post owner can delete it
      return $user->userable_id == $post->author_id;
    }

    public function delete(User $user, Post $post)
    {
      // Only a post owner can delete it
      return $user->userable_id == $post->author_id;
    }

    public function edit(User $user, Post $post){
      // Only a post owner can edit it
      return $user->userable_id == $post->author_id;
    }
}