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
      return $user->userable->regular_user_id == $comment->user_id;
    }

    public function edit(User $user, Comment $comment)
    {
      // Only a comment owner can edit it
      return $user->userable->regular_user_id == $comment->user_id;
    }

    public function like(User $user)
    {
      // Any user can like a comment
      return Auth::check();
    }

    public function report(User $user)
    {
      // Any user can report a comment
      return Auth::check();
    }
}