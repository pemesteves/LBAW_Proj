<?php

namespace App\Policies;

use App\User;
use App\Comment;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class CommentPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
      // Any user can create a new comment
      return Auth::check();
    }

    public function delete(User $user, Comment $comment)
    {
      // Only a comment owner can delete it
      return $user->user_id == $comment->author_id;
    }
}