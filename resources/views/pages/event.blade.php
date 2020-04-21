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
        <img src="images/aefeup.jpg" class="card-img-top mx-auto d-block" alt="...">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title uconnect-title" >{{ $event->name }}</h1>
                <p class="card-text uconnect-paragraph" >{{ $event->information }}</p>
            </div>
            <div class="card-footer container no-gutters">            
                <div class="row">
                    <div class="col-sm-6">
                        <span class="fa fa-calendar"></span> {{ date('d-m-Y, H:i', strtotime($event->date)) }}
                    </div>
                    <div class="col-sm-6">
                        <span class="fa fa-user"></span>&nbsp; {{ $going }} going
                    </div>
                </div>    
                <div class="row" >
                    <div class="col-sm-6">
                        <span class="fa fa-map-pin"></span>&nbsp;&nbsp; {{ $event->location }}
                    </div>
                    <div class="col-sm-6">
                        <span class="fa fa-history"></span>&nbsp; Updated <?= getUpdateDate($event->date); ?> days ago
                    </div>
                </div>   
            </div>
        </div>
    </div>
    
    <div class="col-sm-8" style="flex-grow:1;max-width:100%">

        <form id="post_form" class="new_post">
            <div class="container" id="post_container">
                @csrf
                <input id="post_title" name="title" type="text" required="required" placeholder="Title"/>
                <textarea id="post_text" name="body" class="form-control" required placeholder="Write here..." rows="3"></textarea>
                <div id="post_form_lower">
                    <div id="post_upload">
                        <div>
                            <p class="fa fa-plus">&ensp;image</p>
                            <input type="file"/>
                        </div>
                        <div>
                                <p class="fa fa-plus">&ensp;file</p>
                                <input type="file"/>
                        </div>  
                    </div>
                    <div>
                        <button id="post_form_post" type="submit">Post</button> 
                    </div>
                </div>
            </div>
        </form>

            
        @each('partials.post', $posts, 'post')

    </div>
    
@endsection