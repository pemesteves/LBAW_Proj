@extends('layouts.uconnect_basic')

@section('content')

<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>

<br>
<div id="feed_container" class="container" >
    <div id="profile_card" class="container" style="padding-top: 1em; margin-bottom: 0;">
        <div class="row">
            <div class="col-3">
                <div class="text-center" style="max-width: 75%; max-height: 80%;">
                    <img 
                    @if (isset($image) && $image !== null)
                        src="{{$image->file_path}}"
                    @else
                        src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                    @endif
                    alt="user_image" class="rounded-circle" style="max-width:100%; max-height: 50%;"/>
                </div>
            </div>
            <div class="col-8" style="padding: 0.2rem 1rem 0 0.2rem;">
                <div class="row">
                    <h1 style="border: 0; font-size: 4.5rem;">{{ $user->user->name }}</h1> 
                    @if( get_class($user->regular_userable) == "App\Organization") 
                         @if ($org_status[0]->type == 'accepted') 
                         <span class="fas fa-check-circle">&nbsp;</span>
                         @endif
                    @endif
                </div>
                <div class="row">
                    <h2 style="border: 0; padding: 0">{{$user->university}}</h2>
                    @if( get_class($user->regular_userable) == "App\Organization") 
                        <button type="button" id="org_members_button" class="btn p-0" data-id='{{$user->regular_user_id}}' style="margin-left: auto; margin-right:4%; " data-toggle="modal" 
                                data-target="#viewMembersModal" 
                                style="color: inherit;background: none; width:100%;height:100%;">
                                            <p class="card-text text-left m-0 members_num">{{ 
                                            count($org_members) 
                                            }} members</p>
                                    </button>
                    @endif
                </div>
                <div class="row" style="padding-bottom:10px;">
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
                        @if(Auth::user()->user_id != $user->user_id && !Auth::user()->isAdmin())
                            @if(count($friendship_status) == 0)
                            <span class="btn btn-light add_friend interaction_opt" data-id='{{$user->regular_user_id}}' style="margin-left: auto; margin-right:4% ">
                                Add Friend
                            </span>
                            @elseif($friendship_status[0]->type == 'accepted')
                                <span  class="btn btn-light remove_friend interaction_opt" data-id='{{$user->regular_user_id}}' style="margin-left: auto; margin-right:4%; ">
                                    Remove Friend
                                </span>
                            @elseif($friendship_status[0]->type == 'pending')
                                @if($friendship_status[0]->friend_id1 == Auth::user()->userable_id)
                                    <span  class="btn btn-light cancel_friend interaction_opt" data-id='{{$user->regular_user_id}}' style="margin-left: auto; margin-right:4%; ">
                                        Cancel Request
                                    </span>
                                @else
                                        <span  class="btn btn-light accept_friend interaction_opt" data-id='{{$user->regular_user_id}}' style="margin-left: auto; margin-right:4%; ">
                                            Accept
                                        </span>
                                        <span class="btn btn-light decline_friend interaction_opt" data-id='{{$user->regular_user_id}}' style="margin-right:4%; ">
                                            Decline
                                        </span>
                                @endif
                            @endif

                            @if(get_class($user->regular_userable) == "App\Organization")
                                @if(count($belongToOrg) == 0)
                                <button type="button" class="btn btn-light apply_org" onClick="this.disabled=true" data-id='{{$user->regular_user_id}}' style="margin-left: 0; margin-right:4%;background-color: rgba(0,0,150,.03); ">
                                    Apply to Organization
                                </button>
                                @elseif($belongToOrg[0]->type == 'pending')
                                <button type="button" class="btn btn-light" data-id='{{$user->regular_user_id}}' style="margin-left: 0; margin-right:4%;background-color: rgba(0,0,150,.03); " disabled>
                                    Already applied
                                </button>
                                @elseif($belongToOrg[0]->type == 'accepted')
                                <button type="button" class="btn btn-light" data-id='{{$user->regular_user_id}}' style="margin-left: 0; margin-right:4%;background-color: rgba(0,0,150,.03); " disabled>
                                    Organization member
                                </button>
                                @endif
                            @endif
                        @elseif (Auth::user()->user_id == $user->user_id && get_class($user->regular_userable) == "App\Organization")  
                            @if (count($org_status) == 0)
                                <button type="button" class="btn btn-light verify_org interaction_opt" onClick="this.disabled=true" data-id='{{$user->regular_user_id}}' style="margin-left: auto; margin-right:4%; ">
                                    Verify Organization
                                </button>
                            @elseif($org_status[0]->type == 'pending')
                                <button type="button" class="btn btn-light verify_pending interaction_opt" data-id='{{$user->regular_user_id}}' style="margin-left: auto; margin-right:4%; " disabled>
                                    Request Pending
                                </button>
                            @elseif($org_status[0]->type == 'accepted')
                                <button type="button" class="btn btn-light org interaction_opt" data-id='{{$user->regular_user_id}}' style="margin-left: auto; margin-right:4%; " disabled>
                                    Verified
                                </button>
                            @else
                                <button type="button" class="btn btn-light verify_org interaction_opt" onClick="this.disabled=true" data-id='{{$user->regular_user_id}}' style="margin-left: auto; margin-right:4%;">
                                    Verify Organization
                                </button>
                            @endif
                                

                        @endif
                </div>
                <div class="row">
                    
                </div>
            </div>
            <div class="col-sm-1 d-print-none">
                <div id='user_opt' class="btn-group dropleft" style="margin-right: 0; padding-right: 0; width: 100%">
                    <button type="button" data-toggle="dropdown" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; border: 0;"> 
                    <span class="fa fa-ellipsis-v" ></span></button>
                    <div class="dropdown-menu options_menu" style="min-width:5rem">
                        <ul class="list-group">
                            @if (Auth::user()->user_id == $user->user_id)
                                <li class="list-group-item options_entry" style="text-align: left;">
                                    <button onclick="location.href='/users/me/edit'" style=" margin-left:auto; margin-right:auto; border: 0;">
                                        Edit
                                    </button>
                                </li>
                                <li class="list-group-item options_entry" style="text-align: left;">
                                    <button class='delete' style="border: 0;" > 
                                        Delete
                                    </button>
                                </li>
                                @if (get_class(Auth::user()->userable->regular_userable) == "App\Organization" && $user->regular_user_id == Auth::user()->userable->regular_user_id)
                                <li class="list-group-item options_entry" style="text-align: left;">
                                    <button class='manage manage_applications' id="manage_applications" data-id='{{$user->regular_userable->regular_user_id}}' style="background-color: white; border: 0;" data-toggle="modal" data-target="#viewUserApplications">
                                        Manage members
                                    </button>
                                @endif
                            @else
                                <li class="list-group-item options_entry" style="text-align: left;">
                                    <button class='report' data-id='{{$user->regular_userable->regular_user_id}}' style=" margin-left:auto; margin-right:auto; border: 0;">
                                        Report
                                    </button>
                                </li>
                            @endif
                            
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="accordion" style="width: 100%;">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-accord" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
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
                            <button class="btn btn-accord collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
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
                            <button class="btn btn-accord collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Friends 
                                @if(Auth::user()->user_id != $user->user_id && !Auth::user()->isAdmin()) 
                                    ( {{count(Auth::user()->userable->friendsInCommun($user))}} in common )
                                @endif
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            @if (count($user->friends) === 0)
                                <p>User has no friends yet</p>
                            @else
                                <ul>
                                    @each('partials.user_friend', $user->friends, 'friend')
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                @if(get_class($user->regular_userable) == "App\Teacher")
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h5 class="mb-0">
                            <button class="btn btn-accord collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
                                Agenda  
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Hour</th>
                                    <th scope="col">Monday</th>
                                    <th scope="col">Tuesday</th>
                                    <th scope="col">Wednesday</th>
                                    <th scope="col">Thursday</th>
                                    <th scope="col">Friday</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @for ($i = 0; $i < 13; $i++)
                                    <tr>
                                    <th scope="row">{{$i+7}}h - {{$i+8}}h</th>
                                    @for ($a = 0; $a < 5; $a++)
                                        <td>
                                            @if($user->regular_userable->appointments()[$i*5+$a]->description)
                                                {{$user->regular_userable->appointments()[$i*5+$a]->description}}
                                            @else
                                                ------
                                            @endif
                                        </td>
                                    @endfor
                                    </tr>
                                @endfor 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div id="posts_container" class="col-sm-8" style="flex-grow:1;max-width:100%">
        @if(Auth::user()->user_id == $user->user_id)
            <form method="post" action="/api/posts/" id="post_form" class="new_post d-print-none">
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
        @endif

        <div id="user_posts_container">
            @each('partials.post', $posts, 'post')
        </div>
        

    </div>
    <div class="modal fade" id="viewMembersModal" tabindex="-1" role="dialog" aria-labelledby="viewMembersModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orgMembersLabel">Organization members</h5>
                    <input type="hidden" id="report_id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="all_org_members">
                @if(!is_null($org_members))
                @if(count($org_members) == 0)
                    <p>This org has no users yet!</p>
                @endif
                @foreach($org_members as $member_in_org)
                    <div class="card mb member_card" style="margin-bottom:0px;border-radius:0px;" data-id="{{ $member_in_org->regular_user_id }}">
                        <div class="row no-gutters">
                            <div class="col-md" style="flex-grow:1; max-width:100%; text-align: left;">
                                <a href="../users/{{$member_in_org->regular_user_id}}" style="text-decoration: none; color:black">
                                    <div class="row no-gutters">
                                        <div class="col-sm">
                                            <div class="card text-center" style="border-right:none;border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                <img 
                                                @if (object_get($member_in_org->image(), "image_id"))
                                                src="{{object_get($member_in_org->image(), "file_path")}}"
                                                @else
                                                src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                                                @endif 
                                                class="card-img-top mx-auto d-block" 
                                                alt="..." style="border-radius:50%; width:3rem;height:3rem; padding:0.1rem;padding-top:0.2rem">
                                            </div>
                                        </div>
                                        <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                            <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;display:inline-block;">
                                                    {{$member_in_org->user->name}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a> 
                            </div>
                            @if($member_in_org->regular_user_id == Auth::user()->userable->regular_user_id)
                                <div class="col-sm user_leave_org" style="flex-grow:0; max-width:100%; text-align: left;" data-id='{{$member_in_org->regular_user_id}}'>
                                    <span class="btn btn-light leave_org_button" id="leave_org_button" data-id='{{$member_in_org->regular_user_id}}' 
                                        style="background-color: rgba(0,0,150,.03);float:right; margin-right:0.5rem;margin-top:0.2rem;font-size:0.9rem ">
                                        Leave
                                    </span>
                                </div>
                            @elseif(Auth::user()->userable->regular_user_id == $user->regular_user_id && get_class($user->regular_userable) == "App\Organization")
                                <div class="col-sm user_leave_org" style="flex-grow:0; max-width:100%; text-align: left;" data-id='{{$member_in_org->regular_user_id}}'>
                                        <span class="btn btn-light leave_org_button" id="leave_org_button" data-id='{{$member_in_org->regular_user_id}}' 
                                            style="background-color: rgba(0,0,150,.03);float:right; margin-right:0.5rem;margin-top:0.2rem;font-size:0.9rem ">
                                            Remove
                                        </span>
                                    </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewUserApplications" tabindex="-1" role="dialog" aria-labelledby="viewUserApplicationsLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUserApplicationsLabel">Organization members</h5>
                    <input type="hidden" id="report_id">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
            
                @if(!is_null($org_applyreq))
                @if(count($org_applyreq) == 0)
                    <p id="empty_members">This org has no applications yet!</p>
                @endif
                @foreach($org_applyreq as $member_applied)
                    <div class="card mb member_card member_applied" style="margin-bottom:0px;border-radius:0px;" data-id="{{ $member_applied->regular_user_id }}">
                        <div class="row no-gutters">
                            <div class="col-md" style="flex-grow:1; max-width:100%; text-align: left;">
                                <a href="../users/{{$member_applied->regular_user_id}}" style="text-decoration: none; color:black">
                                    <div class="row no-gutters">
                                        <div class="col-sm">
                                            <div class="card text-center" style="border-right:none;border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                <img 
                                                @if (object_get($member_applied->image(), "image_id"))
                                                src="{{object_get($member_applied->image(), "file_path")}}"
                                                @else
                                                src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                                                @endif 
                                                class="card-img-top mx-auto d-block" 
                                                alt="..." style="border-radius:50%; width:3rem;height:3rem; padding:0.1rem;padding-top:0.2rem">
                                            </div>
                                        </div>
                                        <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                            <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;display:inline-block;">
                                                    {{$member_applied->user->name}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a> 
                            </div>
                                <div class="col-sm accept_div" style="flex-grow:0; max-width:100%; text-align: left;" data-id='{{$member_applied->regular_user_id}}'>
                                    <button type="button" class="btn btn-light accept_user_org" id="accept_user_org" data-id='{{$member_applied->regular_user_id}}' 
                                        style="background-color: rgba(0,0,150,.03);float:right; margin-right:0.5rem;margin-top:0.2rem;font-size:0.9rem ">
                                        Accept
                                    </button>
                                </div>
                                <div class="col-sm reject_div" style="flex-grow:0; max-width:100%; text-align: left;" data-id='{{$member_applied->regular_user_id}}'>
                                    <button type="button" class="btn btn-light reject_user_org" id="reject_user_org" data-id='{{$member_applied->regular_user_id}}' 
                                        style="background-color: rgba(0,0,150,.03);float:right; margin-right:0.5rem;margin-top:0.2rem;font-size:0.9rem ">
                                        Reject
                                    </button>
                                </div>

                        </div>
                    </div>
                @endforeach
                @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



                      
</div>
@endsection