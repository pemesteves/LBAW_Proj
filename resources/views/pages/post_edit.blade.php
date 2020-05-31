@extends('layouts.uconnect_basic')

@section('content')

<article class="post" data-id="{{ $post->post_id }}">
    <div class="modal-dialog" role="document" style="overflow: initial; max-width: 90%; width: 90%; max-height: 90%; height: 90%">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header post_header" >
                <div class="container" style="border-bottom:0;border-radius:0;max-width: 90%;">
                    <div class="row">
                        <div class="col-sm-2">
                            <img @if (Auth::user()->userable->image() !== null)
                                src="{{Auth::user()->userable->image()->file_path}}"
                            @else
                                src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                            @endif
                              class="mx-auto d-block" alt="user_image" style="border-radius:50%; max-width:7rem; " onclick="window.location.href='/users/{{ $post->regularUser->regular_user_id }}'"/>
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
            </div>
            <form action="/posts/{{$post->post_id}}" method="post">
                @csrf
                <div class="modal-body post_container" style="overflow-y: auto; padding-left: 0; padding-right: 0;">
                    <h1 style="border-bottom: 1px solid sandybrown; padding: 0 16px">Edit Post</h1>
                    <div class="container" style="border-bottom:0;border-top:0;border-radius:0;height:100%; margin: 0 auto">
                        <div class="row post_title" style="width:100%; margin: 0;">
                            <h2 style="font-weight: normal">Title:</h2>
                            <h2 style="width: 100%"><input id="post_title" style="width: 100%" name="title" type="text" value="{{$post['title']}}"></input></h2>  
                        </div>
                        <div class="row post_content" style="border-bottom: 0; width: 100%; margin: 0; margin-bottom: 1em">
                            <h2 style="font-weight: normal">Body:</h2>
                            <textarea id="post_text" style="width: 100%; margin: 0;" name="body" type="text" >{{$post['body']}}</textarea>
                        </div>
                    </div>
                </div>
                <div id="post_edit_button" class="modal-footer">
                    <button class="btn btn-primary" type="submit">EDIT POST</button>
                </div>
            </form>
        </div>
    </div>

</article>

@endsection