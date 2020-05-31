<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\OrgApproval;

class OrgApprovalController extends Controller{

    function accept(Request $request, $id){
        $orgRequest = OrgApproval::find($id);
        $this->authorize('approve',$orgRequest);
        $orgRequest->type = 'accepted';
        $orgRequest->save();
        return ['id' => $id];
    }

    function decline(Request $request, $id){
        $orgRequest = OrgApproval::find($id);
        $this->authorize('approve',$orgRequest);
        $orgRequest->delete();
        return ['id' => $id];
    }

}