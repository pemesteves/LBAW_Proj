<?php

namespace App\Policies;

use App\User;
use App\Post;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class CardPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
      // Any user can create a new card
      return Auth::check();
    }

    public function delete(User $user, Post $post)
    {
      // Only a card owner can delete it
      return $user->id == $post->user_id;
    }
}