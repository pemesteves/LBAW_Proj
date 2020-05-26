@extends('layouts.uconnect_basic')

@section('content')
    <div style="margin:2% 0% 0% 15% ;">
        <button class='tab_selector tab_selected' data-id='reports'><h3>Reports</h3></button>
        <button class='tab_selector' data-id='requests'><h3>Approvals</h3></button>
        <button class='tab_selector' data-id='reported'><h3>Reports Accepted</h3></button>
        <button class='tab_selector' data-id='requested'><h3>Approvals Accepted</h3></button>
    </div>

    <div id="reports" class='tab selected'>
        @each('partials.report', $reports, 'report')
    </div>

    <div id="requests" class='tab not_selected'>
        @each('partials.org_approval', $requests, 'request')
    </div>

    <div id="reported" class='tab not_selected'>
        @each('partials.report', $reported, 'report')
    </div>

    <div id="requested" class='tab not_selected'>
        @each('partials.org_approval', $requested, 'request')
    </div>

@endsection