@extends('layouts.uconnect_basic')

@section('content')
<script src="https://js.pusher.com/5.0/pusher.min.js"></script>
<script src="{{ asset('js/echo.js') }}"></script>
<script>
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '05ddfe6c26eaafb78b1b',
    cluster: 'mt1',
    forceTLS: true
});
</script>

<meta name="csrf-token" content="{{ csrf_token() }}" />

<article class="post" data-id="{{ $post->post_id }}">
    <div class="modal-dialog" role="document" style="overflow: initial; max-width: 90%; width: 90%; max-height: 90%; height: 90%">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header post_header" >
                <div class="container" style="border-bottom:0;border-radius:0;max-width: 90%;">
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:7rem; " onclick="window.location.href='/users/{{ $post->regularUser->regular_user_id }}'"/>
                        </div>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-9" style="background-color: transparent;">
                                    <div class="row" style="background-color: transparent;">
                                        <h2 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem"> {{ object_get($post->regularUser->user,"name") }}</h2>
                                    </div>
                                    <div class="row" style="background-color: transparent;">
                                        <h3 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem">{{ $post->uni }}</h3>
                                    </div>
                                </div>
                                <div class="col-sm-3" style="padding-top:0.2rem;padding-bottom:0.2rem; text-align: right; font-size: 1.25em;">
                                    <p class="card-text row" style="margin-bottom:0rem">{{date('d-m-Y', strtotime($post->date))}}</p>
                                    <p class="card-text row">{{date('H:i', strtotime($post->date))}}</p>
                                </div>
                            </div>
                            <div class="row justify-content-end votes" style="font-size: 1.2em;">
                                <div class="col-sm-9" style="padding: 0;"></div>
                                <div class="col-sm-3" style="padding: 0; float:right">
                                    <button class='upvote' style=" background-color: white; border: 0;" > 
                                        <span class="fa fa-thumbs-up post_like">&nbsp;{{ $post->upvotes }}&nbsp;</span>
                                    </button>    
                                    <button class='downvote' style=" background-color: white; border: 0;" > 
                                        <span class="fa fa-thumbs-down post_dislike">&nbsp;{{ $post->downvotes }}&nbsp;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div> 
                    <div class="btn-group dropleft d-print-none" style="margin-right: 0; padding-right: 0; width: 100%">
                        <button type="button" data-toggle="dropdown" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"> 
                        <span class="fa fa-ellipsis-v" ></span></button>
                        <div class="dropdown-menu options_menu" style="min-width:5rem">
                            <ul class="list-group">
                                <li class="list-group-item options_entry" style="text-align: left;">
                                    <button onclick="location.href='/posts/{{$post->post_id}}/edit'" style=" margin-left:auto; margin-right:auto; background-color: white; border: 0;">
                                        Edit
                                    </button>
                                </li>
                                <li class="list-group-item options_entry" style="text-align: left;">
                                    <button class='delete' style=" background-color: white; border: 0;" > 
                                        Delete
                                    </button>
                                </li>
                                <li class="list-group-item options_entry" style="text-align: left;">
                                    <button style="background-color: white; border: 0;">Report</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body post_container" style="overflow-y: auto;">
                <div class="container" style="border-bottom:0;border-top:0;border-radius:0;height:100%;">
                    <div class="row post_title">
                        <h2>{{ $post['title'] }}</h2>  
                    </div>
                    <div class="row post_content">
                        <p> {{ $post['body'] }}</p>
                    </div>
                    <form class="d-print-none">
                        @csrf
                        <div class="row post_comment_form" >
                            <div class="col-2">
                                <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; ">
                            </div>
                            <div class="col-9 post_comment_form_text">
                                <textarea name="body" class="form-control" required placeholder="Comment..." rows="1"></textarea>
                            </div>
                            <div class="col-1" style="padding: 0">
                                <button type="submit" style="padding: 0; max-height: 100%; height: 100%; max-width: 100%; width: 100%; background-color: white; border: 0;"><span class="fa fa-caret-right" style="float: left; font-size: 1.5em;margin-left: 0.75em;"></span></button>
                            </div>
                        </div>
                    </form>
                    <div id ="comments">
                        @each("partials.comment" , $post->comments, "comment")

                        <script>
                            window.Echo.channel('post.{{$post->post_id}}')
                            .listen('NewComment', (e) => {

                                let new_comment = document.createElement('div');
                                new_comment.classList.add('row', 'comment_container', 'comment_no_padding'); 
                                new_comment.setAttribute('data-id',e.comment.comment_id);

                                new_comment.innerHTML = `
                                    <div class="col-sm-2 comment_user_info" >
                                        <div class="row">   
                                            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; "  onclick="window.location.href='./users/${e.comment.user.user_id}'">
                                        </div>
                                        <div class="row">
                                            <h4 style="font-size: 1em; margin: 0 auto;">${e.comment.user.name}</h4>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 comment_text">
                                        <p>${e.comment.body}</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="btn-group dropright d-print-none" style="margin-right: 0; padding-right: 0; width: 100%">
                                            <button type="button" data-toggle="dropdown" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"> 
                                            <span class="fa fa-ellipsis-v" ></span></button>
                                            <div class="dropdown-menu options_menu" style="min-width:5rem">
                                                <ul class="list-group">
                                                    <li class="list-group-item options_entry" style="text-align: left;">
                                                        <button class='comment_edit' style=" margin-left:auto; margin-right:auto; background-color: white; border: 0;">
                                                            Edit
                                                        </button>
                                                    </li>
                                                    <li class="list-group-item options_entry" style="text-align: left;">
                                                        <button class='comment_delete' style=" background-color: white; border: 0;" > 
                                                            Delete
                                                        </button>
                                                    </li>
                                                    <li class="list-group-item options_entry" style="text-align: left;">
                                                        <button style="background-color: white; border: 0;">
                                                            Report
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>`

                                 document.getElementById("comments").insertBefore(new_comment, document.getElementById("comments").firstChild);
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>

</article>

@endsection