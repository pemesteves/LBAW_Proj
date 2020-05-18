<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\OrgApproval;

class OrgApprovalController extends Controller{

    function accept(Request $request, $id){
        $orgRequest = OrgApproval::find($id);
        $orgRequest->approval = true;
        $orgRequest->save();
        return ['id' => $id];
    }

    function decline(Request $request, $id){
        $orgRequest = OrgApproval::find($id);
        $orgRequest->approval = false;
        $orgRequest->save();
        return ['id' => $id];
    }

}