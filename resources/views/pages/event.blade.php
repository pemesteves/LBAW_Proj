@extends('layouts.uconnect_basic')

<?php
    function getUpdateDate($date){
        $datetime1 = new DateTime($date);
        $datetime2 = new DateTime();

        return date_diff($datetime1, $datetime2, true)->format("%a");
    }
?>


<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>


@section('content')
<div id="feed_container" class="container" >
    <div id="event_card" class="container card mb-3 border rounded">
        <div class="row">      
            <img 
            @if (isset($image) && $image !== null)
                src="{{$image->file_path}}"
            @else
                src=""
            @endif
            class="card-img-top mx-auto d-block" alt="event_image"/>
        </div>
        <div class="card row">
            <div class="row">
                <div class="col-sm-11">
                    <div class="card-body">
                        <h1 class="card-title uconnect-title" style='display:inline-block'>{{ $event->name }}</h1>
                        @if (!(Auth::user()->userable->regular_userable_type == 'App\Organization' && Auth::user()->userable->regular_userable->organization_id == $event->organization_id))
                            @if(count($interested) == 0)
                                <button type="button" class="btn btn-light show_interest interaction_opt" data-id='{{$event->event_id}}' 
                                    style="float:right;margin-right:20px;">
                                    Show interest
                                </button>
                            @else
                                <button type="button" class="btn btn-light remove_interest interaction_opt" data-id='{{$event->event_id}}' 
                                    style="float:right;margin-right:20px;">
                                    Remove interest
                                </button>
                            @endif
                        @endif
                        <p class="card-text uconnect-paragraph" >{{ $event->information }}</p>
                    </div>
                </div>
                <div class="col-sm-1 d-print-none">
                    <div class="btn-group dropleft" style="margin-right: 0; padding-right: 0; width: 100%">
                        <button type="button" data-toggle="dropdown" style="margin-top:5px;font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; border: 0; background-color: transparent"> 
                        <span class="fa fa-ellipsis-v" ></span></button>
                        <div class="dropdown-menu options_menu" id="event_menu_options" style="min-width:5.5rem;">
                            <ul class="list-group">
                                @if ($is_owner)
                                    <li class="list-group-item options_entry" style="text-align: left;">
                                        <button onclick="location.href='/events/{{$event->event_id}}/statistics'" style=" margin-left:auto; margin-right:auto; border: 0; background-color: transparent">
                                            Statistics
                                        </button>
                                    </li>
                                    <li class="list-group-item options_entry" style="text-align: left;">
                                        <button onclick="location.href='/events/{{$event->event_id}}/edit'" style=" margin-left:auto; margin-right:auto; border: 0; background-color: transparent">
                                            Edit
                                        </button>
                                    </li>
                                    <li class="list-group-item options_entry" style="text-align: left; ">
                                        <button class='delete' style="border: 0; background-color: transparent" > 
                                            Delete
                                        </button>
                                    </li>
                                @else
                                    <li class="list-group-item options_entry" style="text-align: left;">
                                        <button class='report' style="border: 0; background-color: transparent" data-id='{{$event->event_id}}' > 
                                            Report
                                        </button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="card-footer no-gutters">            
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

        <form method="post" action="/api/events/{{$event->event_id}}/posts/" id="post_form" class="new_post d-print-none" enctype="multipart/form-data">
            <div class="container" id="post_container">
                @csrf
                <input id="post_title" name="title" type="text" required="required" placeholder="Title"/>
                <textarea id="post_text" name="body" class="form-control" required placeholder="Write here..." rows="3"></textarea>
                <div id="post_form_lower">
                    <div id="post_upload">
                        <div>
                            <p class="fa fa-plus">&ensp;image</p>
                            <input type="file" name="image"/>
                        </div>
                        <div>
                            <p class="fa fa-plus">&ensp;file</p>
                            <input type="file" name="file"/>
                        </div>  
                    </div>
                    <div>
                        <button id="post_form_post" type="submit">Post</button> 
                    </div>
                </div>
                <div class="container" id="postInputImages" style="display:none;">
                    <div class="row">
                        <div class="col-sm-6">    
                            <img id="image" src="" style="display:none;"/>
                        </div>
                        <div class="col-sm-6">
                            <img class="file" style="display:none;"/>
                            <canvas class="file" style="display:none;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div id='event_form_container'>
            
        @each('partials.post', $posts, 'post')
        </div>

    </div>
</div>
@endsection