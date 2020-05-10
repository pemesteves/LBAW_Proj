@extends('layouts.uconnect_basic')

@section('content')

<div class="container" style="margin:0; min-width:100%">
        <div class="row">
            <div class="col-sm-2" style="padding-left:0px">
                <div id="dl-menu" class="dl-menuwrapper">
                    <button id="dl-trigger" onclick="toggle()">Open Menu</button>
                    <ul id="dl-menu2">
                        <li>
                            <h5 class="menu_title">Groups</h5>
                            <ul class="dl-submenu">
                                @if (count($groups) === 0)
                                    <small>No groups</small>
                                @else
                                    @foreach ($groups as $group)
                                        <li><a href="groups/{{$group->group_id}}"><small>{{$group->name}}</small></a></li>
                                    @endforeach
                                @endif
                                <li><button class="btn btn-primary" onclick="window.location.href='/groups/create'"><span class="fa fa-plus"></span>&nbsp;Create Group</button>
                            </ul>
                        </li>
                        <li>
                            <h5 class="menu_title">EnewMessagevents</h5>
                            <ul class="dl-submenu">
                                @if (count($events) === 0)
                                    <small>No events</small>
                                @else
                                    @foreach ($events as $event)
                                        <li><a href="events/{{$event->event_id}}"><small>{{$event->name}}</small></a></li>
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
            <div class="col-sm-2">

            </div>
        </div>
    </div>

@endsection