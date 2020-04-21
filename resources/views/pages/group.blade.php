@extends('layouts.uconnect_basic')

<?php
    function getUpdateDate($date){
        $datetime1 = new DateTime($date);
        $datetime2 = new DateTime();

        return date_diff($datetime1, $datetime2, true)->format("%a");
    }
?>

@section('content')

    <div id="group_card" class="card mb-3 border rounded">
        <div class="row no-gutters">
            <div class="card text-center col-sm-3">
                <img src="http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png" class="card-img-top mx-auto d-block" alt="..." style="border-radius:50%; max-width:8rem">
            </div>
            <div class="card col-sm-9" >
                <div class="card-body">
                    <h1 class="card-title">{{ $group->name }}</h1>
                    <h2 class="card-subtitle">{{ $group->information }} </h2>
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="card-text"><!--$members?>--> members</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="card-text" id="last_update"><span class="fa fa-history"></span>&nbsp;Updated <?= getUpdateDate($group->updated_at);?><!--2--> days ago</p>
                            </div>
                        </div>
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