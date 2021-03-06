@extends('layouts.uconnect_basic')

@section('content')
    <div id="search_container" class='container'>
    @if($users)
        <div class="searchDiv">
            <h2>Users</h2>
            <div>
            @if (count($users) == 0)
                <div class="row">
                    <p>Can't find Users</p>
                </div>
            </div>
            @else
                @foreach ($users as $obj)
                    <div class="row searchDivCard" 
                         onclick="window.location.href='/users/{{$obj->regularUserId}}'">
                        <div class="col-sm-2">
                            <img class="rounded-circle" style='width:10rem;height:10rem;'
                            @if (isset($obj->file_path) && $obj->file_path !== null)
                                src="{{$obj->file_path}}"
                            @else
                                src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                            @endif
                            />
                        </div>
                        <div class="col-sm-10">
                            <h3>{{$obj->name}}</h3>
                            <p><?=explode("\\", $obj->regular_userable_type)[1];?> at {{$obj->university}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href='/users/search?search={{$str}}'><span class="fa fa-plus" style='background-color:transparent;'></span> More users </a>
            @endif
        </div>
    @endif
    @if($events)
        <div class="searchDiv">
            <h2>Events</h2>
            <div>
            @if (count($events) == 0)
                <div class="row">
                    <p>Can't find Events</p>
                </div>
            </div>
            @else
                @foreach ($events as $obj)
                    <div class="row searchDivCard" 
                         onclick="window.location.href='/events/{{$obj->eventId}}'">
                        <div class="col-sm-2">
                            <img class="rounded-circle"  style='width:10rem;height:10rem;'
                            @if (isset($obj->file_path) && $obj->file_path !== null)
                                src="{{$obj->file_path}}"
                            @else
                                src="http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png" 
                            @endif
                            />
                        </div>
                        <div class="col-sm-10">
                            <h3>{{$obj->eventName}}</h3>
                            <p>By {{$obj->name}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href='/events/search?search={{$str}}'> <span class="fa fa-plus" style='background-color:transparent;'></span> More events </a>
            @endif
        </div>
    @endif
    @if($groups)
        <div class="searchDiv">
            <h2>Groups</h2>
            <div>
            @if (count($groups) == 0)
                <div class="row">
                    <p>Can't find Groups</p>
                </div>
            </div>
            @else
                @foreach ($groups as $obj)
                <div class="row searchDivCard" 
                         onclick="window.location.href='/groups/{{$obj->groupId}}'">
                        <div class="col-sm-2">
                            <img class="rounded-circle"  style='width:10rem;height:10rem;'
                            @if (isset($obj->file_path) && $obj->file_path !== null)
                                src="{{$obj->file_path}}"
                            @else
                                src="http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png"  
                            @endif
                            />
                        </div>
                        <div class="col-sm-10">
                            <h3>{{$obj->name}}</h3>
                            <p>{{$obj->information}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href='/groups/search?search={{$str}}'> <span class="fa fa-plus" style='background-color:transparent;'></span> More groups </a>
            @endif
        </div>
    @endif
    @if($posts !== null)
        <div class="searchDiv">
            <h2>Posts</h2>
            <div>
            @if (count($posts) == 0)
                <div class="row">
                    <p>Can't find Posts</p>
                </div>
            </div>
            @else
                @foreach ($posts as $obj)
                    @if($obj->rank > 0.0)
                    <div class="col-sm-12 searchDivCard" 
                         onclick="window.location.href='/posts/{{$obj->post_id}}'">
                            <div class="row" style="padding: 0">
                                <h3>{{$obj->title}}</h3>
                            </div>
                            <div class="row" style="padding: 0">
                                <div class="col-sm-6" style="padding-left: 0">
                                    <p>{{$obj->body}}</p>
                                </div>
                                <div class="col-sm-6" style="padding-right: 0; text-align:right">
                                    <p>{{date('d-m-Y, H:i', strtotime($obj->date))}}</p>
                                </div>
                            </div>
                    </div>
                    @endif
                @endforeach
            </div>
            <a href='/posts/search?search={{$str}}'> <span class="fa fa-plus" style='background-color:transparent;'></span> More posts </a>
            @endif
        </div>
    @endif
    </div>
@endsection