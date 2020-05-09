@extends('layouts.uconnect_basic')

@section('content')

    <div>

        @each('partials.report', $reports, 'report')

    </div>

@endsection