<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\OrgApproval;

class OrgApprovalController extends Controller{

    function accept(Request $request, $id){
        $orgRequest = DB::table('organization_approval_request')
            ->where(['organization_approval_request.request_id' => $id])
            ->update(['type' => 'accepted']);
        return ['id' => $id];
    }

    function decline(Request $request, $id){
        $orgRequest = DB::table('organization_approval_request')
        ->where(['organization_approval_request.request_id' => $id])
        ->update(['type' => 'refused']);
    return ['id' => $id];
    }

}