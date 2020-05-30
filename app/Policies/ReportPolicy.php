<?php

namespace App\Policies;

use App\User;
use App\Report;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ReportPolicy
{
    use HandlesAuthorization;

    public function approve(User $user,Report $report)
    {
      // Only Admin can approve or ignore
      return $user->isAdmin();
    }

}