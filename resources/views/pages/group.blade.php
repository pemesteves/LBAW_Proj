@extends('layouts.uconnect_basic')

<?php
    function getUpdateDate($date){
        $datetime1 = new DateTime($date);
        $datetime2 = new DateTime();

        return date_diff($datetime1, $datetime2, true)->format("%a");
    }
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>


@section('content')
<div id="feed_container" class="container" data-id="{{ $group->group_id }}">
    <div id="group_card" class="container card mb-3 border rounded">
        <div class="row no-gutters">
            <div class="card text-center col-sm-3">
                <img 
                    @if (isset($image) && $image !== null)
                        src="{{$image->file_path}}"
                    @else
                        src="http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png" 
                    @endif
                class="card-img-top mx-auto d-block" alt="group_image" style="border-radius:50%; max-width:8rem">
            </div>
            <div class="card col-sm-9" >
                <div class="row">
                    <div class="col-sm-11">
                        <div class="card-body">
                            <h1 class="card-title">{{ $group->name }}</h1>
                            <h2 class="card-subtitle">{{ $group->information }} </h2>
                            <div class="row">
                                @if($is_owner)
                                <div class="col-sm flex-grow-0 p-0">
                                    <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="addMemberModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addMemberModalLabel">Add new members</h5>
                                                    <input type="hidden" id="report_id">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action='/users/delete' method='post'>
                                                    <div class="form-group">
                                                        <label class="col-form-label">Member:</label>
                                                        <input type="text" name='name' autocomplete="off" label='name' class="form-control" id="new_member_name">
                                                    </div>
                                                    </form>
                                                    <div id='members_search'>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button id='add_members_group' class='btn btn-light' style='border-radius:50%;width:40px;height:40px' data-toggle="modal" data-target="#addMemberModal">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                </div>
                                @endif
                                <div class="col-sm p-0">
                                    <div class="modal fade p-5" id="memberPopup" tabindex="-1" role="dialog" 
                                        aria-labelledby="memberButton" aria-hidden="true">  
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">          
                                                @foreach($members as $member)
                                                    <div class="card mb member_card" style="margin-bottom:0px;border-radius:0px;" data-id="{{ $member->regular_user_id }}">
                                                        <div class="row no-gutters">
                                                            <div class="col-md" style="flex-grow:1; max-width:100%; text-align: left;">
                                                                <a href="../users/{{$member->regular_user_id}}" style="text-decoration: none; color:black">
                                                                    <div class="row no-gutters">
                                                                        <div class="col-sm">
                                                                            <div class="card text-center" style="border-right:none;border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                                                <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" 
                                                                                alt="user_image" style="border-radius:50%; max-width:3rem; padding:0.1rem;padding-top:0.2rem">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                                                            <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                                                <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;display:inline-block;">
                                                                                    {{$member->name}}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </a> 
                                                            </div>
                                                            @if($is_owner)
                                                            <div class="col-sm" style="flex-grow:0; max-width:100%; text-align: left;">
                                                                <span class="btn btn-light remove_button" data-id='{{$member->regular_user_id}}' 
                                                                    style="background-color: rgba(0,0,150,.03);float:right; margin-right:0.5rem;margin-top:0.2rem;font-size:0.9rem ">
                                                                    Remove
                                                                </span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="memberButton" class="btn p-0" data-toggle="modal" 
                                data-target="#memberPopup" 
                                style="color: inherit;background: none; width:100%;height:100%;">
                                            <p class="card-text text-left m-0"><!--$members?>-->{{ $member_count }} members</p>
                                    </button>
                                </div>
                                
                                <div class="col-sm ml-auto">
                                    <p class="card-text" id="last_update"><span class="fa fa-history"></span>&nbsp;Updated <?= getUpdateDate($group->updated_at);?><!--2--> days ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1 d-print-none">
                        <div class="btn-group dropleft" style="margin-right: 0; padding-right: 0; width: 100%">
                            <button type="button" data-toggle="dropdown" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"> 
                            <span class="fa fa-ellipsis-v" ></span></button>
                            <div class="dropdown-menu options_menu" id="group_menu_options" style="min-width:5rem">
                                <ul class="list-group">
                                    @if ($is_owner)
                                        <li class="list-group-item options_entry" style="text-align: left;">
                                            <button onclick="location.href='/groups/{{$group->group_id}}/edit'" style=" margin-left:auto; margin-right:auto; background-color: white; border: 0;">
                                                Edit
                                            </button>
                                        </li>
                                        <li class="list-group-item options_entry" style="text-align: left;">
                                            <button class='delete' style=" background-color: white; border: 0;" > 
                                                Delete
                                            </button>
                                        </li>
                                    
                                        <li class="list-group-item options_entry" style="text-align: left;">
                                            <button class='report' style=" background-color: white; border: 0;" data-id='{{$group->group_id}}' > 
                                                Report
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-8" style="flex-grow:1;max-width:100%">

        <form id="post_form" class="new_post d-print-none" enctype="multipart/form-data">
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
        <div id='group_form_container'>
            @each('partials.post', $posts, 'post')

        </div>

    </div>
</div>  
@endsection