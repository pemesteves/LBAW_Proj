@extends('layouts.uconnect_basic')

@section('content')

<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>

<div id="feed_container" class="container" >
        <div class="row">
            <div id="sidebar" class="col-sm-2 d-print-none">
                <div id="dl-menu" class="dl-menuwrapper">
                    <button id="dl-trigger" onclick="toggle()">Open Menu</button>
                    <ul id="dl-menu2">
                        <li>
                            <h5 class="menu_title">Groups</h5>
                            <ul class="dl-submenu">
                                @if (count($groups) === 0)
                                    <li><small>No groups</small></li>
                                @else
                                    @foreach ($groups as $group)
                                        <li><a href="groups/{{$group->group_id}}"><small class='sub-menu'>{{$group->name}}</small></a></li>
                                    @endforeach
                                @endif
                                <li><button class="btn btn-primary" onclick="window.location.href='/groups/create'"><span class="fa fa-plus"></span>&nbsp;Create Group</button>
                            </ul>
                        </li>
                        <li>


                            <h5 class="menu_title">Events</h5>
                            <ul class="dl-submenu">
                                @if (count($events) === 0)
                                    <li><small>No events</small></li>
                                @else
                                    @foreach ($events as $event)
                                        <li><a href="events/{{$event->event_id}}"><small class='sub-menu'>{{$event->name}}</small></a></li>
                                    @endforeach
                                @endif

                                @if ($can_create_events)
                                    <li><button class="btn btn-primary" onclick="window.location.href='/events/create'"><span class="fa fa-plus"></span>&nbsp;Create Event</button>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
                <script> 
                    function toggle() { 
                        let opacity = document.querySelector('#dl-menu2').style.opacity; 
                        if(opacity == "1"){
                            document.querySelector('#dl-menu2').style.opacity=0;
                            document.querySelector('#dl-menu2').style.display="none";
                        }
                        else{
                            document.querySelector('#dl-menu2').style.opacity=1;
                            document.querySelector('#dl-menu2').style.display="block";
                        }
                    } 
                </script> 
            

            </div>
            <div id='postFeed_container' class="col-sm-8">

                <form id="post_form" class="new_post d-print-none" enctype="multipart/form-data">
                    <div id="post_container">
                        @csrf
                        <input id="post_title" name="title" type="text" required="required" placeholder="Title"/>
                        <textarea id="post_text" name="body" class="form-control" required placeholder="Write here..." rows="3"></textarea>
                        <div id="post_form_lower" class="container">
                            <div id="post_upload" class="col-sm-11" style="padding: 0;">
                                <div>
                                    <p class="fa fa-plus">&ensp;image</p>
                                    <input type="file" name="image"/>
                                </div>
                                <div>
                                    <p class="fa fa-plus">&ensp;file</p>
                                    <input type="file" name="file"/>
                                </div>  
                            </div>
                            <div class="col-sm-1" style="padding: 0;">
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

                @if (count($posts) === 0)
                    <h4 style="text-align:center">No posts to see, let's make friends!</h4>
                @else
                    @each('partials.post', $posts, 'post')
                @endif

            </div>
            <div class="col-sm-2 d-print-none">
                <div style="position: absolute;top: 36vh;transform: translateY(-50%);">
                    @each('partials.recommendFriend', $recommendations, 'user')
                </div>
            </div>
        </div>
    </div>

@endsection