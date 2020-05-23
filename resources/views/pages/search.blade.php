@extends('layouts.uconnect_basic')

@section('content')
    <br>
    @if($users)
        @foreach ($users as $obj)
            {{$obj->name}}
            <br>
        @endforeach
        <a href='/users/search?search={{$str}}'> More users </a>
        <br><br><br>
    @endif
    @if($events)
        @foreach ($events as $obj)
            {{$obj->name}}
            <br>
        @endforeach
        <a href='/events/search?search={{$str}}'> More events </a>
        <br><br><br>
    @endif
    @if($groups)
        @foreach ($groups as $obj)
            {{$obj->name}}
            <br>
        @endforeach
        <a href='/groups/search?search={{$str}}'> More groups </a>
    @endif
@endsection