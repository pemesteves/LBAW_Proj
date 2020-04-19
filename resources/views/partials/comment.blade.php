

<div class="row comment_container <?= $comment['padding_left'] != 0 ? 'comment_padding' : 'comment_no_padding' ?>">
    <div class="col-2 comment_user_info" >
        <div class="row">   
            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; "  onclick="window.location.href='./profile.php'">
        </div>
        <div class="row">
            <h4 style="font-size: 1em; margin: 0 auto;">{{ $comment->user->name }}</h4>
        </div>
    </div>
    <div class="col-9 comment_text">
        <p>{{ $comment->body }}</p>
    </div>
    <div class="col-1 comment_opt">
        <button><span class="fa fa-ellipsis-v"></span></button>
    </div>
</div>