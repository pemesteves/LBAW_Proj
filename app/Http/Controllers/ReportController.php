<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Report;

class ReportController extends Controller{

    function accept(Request $request, $id){
        $report = Report::find($id);
        $report->approval = true;
        $report->save();
        return ['id' => $id];
    }

    function decline(Request $request, $id){
        $report = Report::find($id);
        $report->approval = false;
        $report->save();
        return ['id' => $id];
    }

}