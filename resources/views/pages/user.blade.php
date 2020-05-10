@extends('layouts.uconnect_basic')

@section('content')

<br>
    <div id="profile_card" class="container" style="padding-top: 1em; margin-bottom: 0; background-color: white; border: 1px solid lightgrey;">
        <div class="row">
            <div class="col-4">
                <div class="text-center" style="max-width: 75%; max-height: 80%;">
                    <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" alt="" class="rounded-circle" style="max-width:100%; max-height: 50%;"/>
                </div>
            </div>
            <div class="col-8" style="padding: 0.2rem 1rem 0 0.2rem;">
                <div class="row">
                    <h1 style="border: 0; font-size: 4.5rem;">{{ $user->user->name }}</h1>
                </div>
                <div class="row">
                    <h2 style="border: 0; padding: 0">{{$user->university}}</h2>
                </div>
                <div class="row">
                    <h2 style="border: 0; padding: 0;">
                    @switch(get_class($user->regular_userable))
                        @case("App\Student")
                            Student
                        @break;
                        @case("App\Teacher")
                            Teacher
                        @break;
                        @case("App\Organization")
                            Organization
                        @break;
                    @endswitch
                    </h2>
                </div>
                <div class="row">
                    @if(Auth::user()->user_id != $user->user_id)
                        @if(count($friendship_status) == 0)
                        <button type="button" class="btn btn-light add_friend" style="margin-left: auto; margin-right:5%;background-color: rgba(0,0,150,.03); ">
                            Add Friend
                        </button>
                        @elseif($friendship_status[0]->type == 'accepted')
                            <button type="button" class="btn btn-light remove_friend" style="margin-left: auto; margin-right:5%;background-color: rgba(0,0,150,.03); ">
                                Remove Friend
                            </button>
                        @elseif($friendship_status[0]->type == 'pending')
                            <button type="button" class="btn btn-light cancel_friend" style="margin-left: auto; margin-right:5%;background-color: rgba(0,0,150,.03); ">
                                Cancel Request
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div id="accordion" style="width: 100%;">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Personal Information
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            @if( Auth::user()->userable->personal_info == "")
                                No information yet.
                            @else
                                {{$user->personal_info}}
                            @endif    
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Groups
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            @if (count($groups) === 0)
                                <p>No groups</p>
                            @else
                                <ul>
                                    @each('partials.user_group', $groups, 'group')
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Friends
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            @if (count($friends) === 0)
                                <p>User has no friends yet</p>
                            @else
                                <ul>
                                    @each('partials.user_friend', $friends, 'friend')
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8" style="flex-grow:1;max-width:100%">
        @if(Auth::user()->user_id == $user->user_id)
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
        @endif
            
        @each('partials.post', $posts, 'post')

    </div>
    
@endsection