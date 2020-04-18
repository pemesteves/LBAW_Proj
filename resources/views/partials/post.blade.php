




<!-- Popup -->

<div class="modal fade" id="popup-{{ $post['id'] }}" tabindex="-1" role="dialog" aria-labelledby="postModal-{{ $post['id'] }}"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="overflow: initial; max-width: 90%; width: 90%; max-height: 90%; height: 90%">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header post_header" >
                <div class="container" style="border-bottom:0;border-radius:0;max-width: 90%;">
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:7rem; " onclick="window.location.href='./profile.php'"/>
                        </div>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-9" style="background-color: transparent;">
                                    <div class="row" style="background-color: transparent;">
                                        <h2 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem">{{ $post['author'] }}</h2>
                                    </div>
                                    <div class="row" style="background-color: transparent;">
                                        <h3 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem">{{ $post['uni'] }}</h3>
                                    </div>
                                </div>
                                <div class="col-sm-3" style="padding-top:0.2rem;padding-bottom:0.2rem; text-align: right; font-size: 1.25em;">
                                    <p class="card-text" style="margin-bottom:0rem">{{ $post['date'] }}</p>
                                    <p class="card-text">{{ $post['hour'] }}</p>
                                </div>
                            </div>
                            <div class="row justify-content-end" style="font-size: 1.2em;">
                                <span class="fa fa-thumbs-up post_like">&nbsp;{{ $post['likes'] }}&nbsp;</span>
                                <span class="fa fa-thumbs-down post_dislike">&nbsp;{{ $post['dislikes'] }}&nbsp;</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" data-dismiss="modal" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"><span class="fa fa-times"></span></button>
                    <button type="button" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"> <span class="fa fa-ellipsis-v" ></span></button>
                </div>
            </div>
            <div class="modal-body post_container" style="overflow-y: auto;">
                <div class="container" style="border-bottom:0;border-top:0;border-radius:0;height:100%;">
                    <div class="row">
                        <h2>{{ $post['title'] }}</h2>  
                    </div>
                    <div class="row post_content">
                        <p> {{ $post['post_content'] }}</p>
                    </div>
                    <form method="post">
                        <div class="row post_comment_form" >
                            <div class="col-2">
                                <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; ">
                            </div>
                            <div class="col-9 post_comment_form_text">
                                <textarea class="form-control" required placeholder="Comment..." rows="1"></textarea>
                            </div>
                            <div class="col-1" style="padding: 0">
                                <button type="submit" style="padding: 0; max-height: 100%; height: 100%; max-width: 100%; width: 100%; background-color: white; border: 0;"><span class="fa fa-caret-right" style="float: left; font-size: 1.5em;margin-left: 0.75em;"></span></button>
                            </div>
                        </div>
                    </form>
                    <div style="">
                        @each('partials.comment', $post['comments'] , 'comment')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>








<!-- Post -->



<div class="post card mb-3" style="max-width:70%;margin:5% 15%">
    <button type="button" id="postModal-"{{ $post['id'] }} class="btn btn-primary" data-toggle="modal" data-target="#popup-{{ $post['id'] }}" style="text-align:left;background: none; color: inherit; border: none; padding: 0; font: inherit; cursor: pointer; outline: inherit;"> 
        <div class="row no-gutters">
            <div class="col-sm">
                <div class="card text-center" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                    <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" alt="..." style="border-radius:50%; max-width:5rem; padding-top:0.8rem">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem"> {{ $post['author'] }}
                        </li>
                        <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem">{{ $post['uni'] }}</li>
                        <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem">4 friends
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                <div class="card" style="height: 100%; margin-bottom: 0;">
                    <div class="card-body" style="margin-bottom: 0;padding-bottom: 0;">
                        <h3 class="card-title"> {{ $post['title'] }}</h3>
                        <p class="card-text">
                        {{ $post['post_content'] }}
                        </p>
                        <p class="card-text" style="margin-bottom:0rem; float: right;"><small class="text-muted" style="margin-bottom:0rem">{{ $post['date'] }}</small>, <small class="text-muted" style="margin-bottom:0.2rem">{{ $post['hour'] }}</small></p>
                    </div>
                    <div class="card-footer" style="border-left:none;border-right:none;border-bottom:none">
                        <span class="comment"> 2 comments </span>
                        <div style="float: right;">
                            <span class="fa fa-thumbs-up post_like">&nbsp;{{ $post['likes'] }}&nbsp;</span>
                            <span class="fa fa-thumbs-down post_dislike">&nbsp;{{ $post['dislikes'] }}&nbsp;</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </button>
</div>