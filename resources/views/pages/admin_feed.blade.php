@extends('layouts.uconnect_basic')

@section('content')

    <div id="reports">

        @each('partials.report', $reports, 'report')

    </div>

    <div id="requests">

        @each('partials.org_approval', $requests, 'request')

    </div>

@endsection