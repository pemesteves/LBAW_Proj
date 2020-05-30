@extends('layouts.uconnect_basic')

<?php
    function getUpdateDate($date){
        $datetime1 = new DateTime($date);
        $datetime2 = new DateTime();

        return date_diff($datetime1, $datetime2, true)->format("%a");
    }
?>

@section('content')
<div id="feed_container" class="container" >
    <div id="group_card" class="container card mb-3 border rounded">
        <div class="row no-gutters">
            <div class="card text-center col-sm-3">
                <img 
                    @if (isset($image) && $image !== null)
                        src="{{$image->file_path}}"
                    @else
                        src="http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png" 
                    @endif
                class="card-img-top mx-auto d-block" alt="..." style="border-radius:50%; max-width:8rem">
            </div>
            <div class="card col-sm-9" >
                <div class="row">
                    <div class="col-sm-11">
                        <div class="card-body">
                            <h1 class="card-title">{{ $group->name }}</h1>
                            <h2 class="card-subtitle">{{ $group->information }} </h2>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <p class="card-text m-0"><!--$members?>-->{{ $member_count }} members</p>
                                        <button class="btn dropdown-toggle dropdown-toggle-split py-0" type="button" id="memberDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="memberDropdown">            
                                            <div class="navbar-nav">
                                                @each('partials.group_member', $members, 'member')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
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

        <form id="post_form" class="new_post d-print-none">
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
</div>  
@endsection