@extends('layouts.uconnect_basic')

<?php
    function getUpdateDate($date){
        $datetime1 = new DateTime($date);
        $datetime2 = new DateTime();

        return date_diff($datetime1, $datetime2, true)->format("%a");
    }
?>

@section('content')
<div id="event_card" class="card mb-3 border rounded">
    <form method="post" action="/events/{{$event->event_id}}">
        @csrf
        <img src="images/aefeup.jpg" class="card-img-top mx-auto d-block" alt="..."> <!--Add image upload -->
        <div class="card">
            <div class="card-body">
                <legend class="card-title uconnect-title" >Name: </legend>
                <h1 class="card-title uconnect-title" >
                    <input id="name" type="text" name="name" placeholder="Event Name" value="{{ $event->name }}" required/>
                </h1>
                <legend>Information:</legend>
                <p class="card-text uconnect-paragraph" >
                    <textarea id="information" name="information" type="text" required>{{ $event->information }}</textarea>
                </p>
                <div class="row">
                    <div class="col-sm-6">
                        <legend><span class="fa fa-calendar"></span>&nbsp;Date:</legend>
                        <input type="date" name="date" value="{{ date('Y-m-d', strtotime($event->date)) }}" required/> <!--Change calendar-->
                    </div>
                    <div class="col-sm-6">
                        <legend><span class="fa fa-map-pin"></span>&nbsp;Location:</legend>
                        <input type="text" name="location" value="{{$event->location}}" required/> <!--Add Google Maps API-->
                    </div>
                </div>   
            </div>       
            <div class="card-footer container no-gutters">  
                <button type="submit" class="btn-primary">Edit Event</button>      
            </div>
        </div>
    </form>
</div>
    
@endsection