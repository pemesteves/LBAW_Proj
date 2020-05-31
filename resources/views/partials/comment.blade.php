
<div class="row comment_container 
    <?= $comment['padding_left'] != 0 ? 'comment_padding' : 'comment_no_padding' ?>"
    data-id="{{ $comment->comment_id }}" id="comment_{{ $comment->comment_id }}">
    <div class="col-sm-2 comment_user_info" >
        <div class="row">   
            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="author_image" style="border-radius:50%; max-width:2rem; "  onclick="window.location.href='./profile.php'">
        </div>
        <div class="row">
            <h4 style="font-size: 1em; margin: 0 auto;">{{ $comment->user->user->name }}</h4>
        </div>
    </div>
    <div class="col-sm-9 comment_text">
        <h6>{{ $comment->body }}</h6>
    </div>
    <div class='comment_votes' style="float: right;">
            <button class='comment_upvote' style="padding-left:4px; background-color: transparent; border: 0;" > 
                <span class="fa fa-thumbs-up comment_like">&nbsp;{{$comment->upvotes}}&nbsp;</span>
            </button>    
            <button class='comment_downvote' style="padding-left:0; background-color: transparent; border: 0;" > 
                <span class="fa fa-thumbs-down comment_dislike">&nbsp;{{$comment->downvotes}}&nbsp;</span>
            </button>
        </div>
    <div >
        <div class="btn-group dropright d-print-none" style="margin-right: 0; padding-right: 0; width: 100%">
            <button type="button" data-toggle="dropdown" style="padding:0;font-size: 100%;width:20px; margin-right: 0; padding-left: 1px;padding-right: 1px; width: 100%; background-color: white; border: 0;"> 
            <span class="fa fa-ellipsis-v" ></span></button>
            <div class="dropdown-menu options_menu" style="min-width:5rem">
                <ul class="list-group">
                    @if($comment->user_id == Auth::user()->userable->regular_user_id)
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
                    @else
                        <li class="list-group-item options_entry" style="text-align: left;">
                            <button class='comment_report' style=" background-color: white; border: 0;" > 
                                Report
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
