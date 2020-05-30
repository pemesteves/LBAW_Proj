<?php

namespace App\Policies;

use App\User;
use App\OrgApproval;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class OrgApprovalPolicy
{
    use HandlesAuthorization;

    public function approve(User $user, OrgApproval $approval)
    {
      // Only Admin can approve or ignore
      return $user->isAdmin();
    }

}