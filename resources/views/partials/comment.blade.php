
<div class="row comment_container 
    <?= $comment['padding_left'] != 0 ? 'comment_padding' : 'comment_no_padding' ?>"
    data-id="{{ $comment->comment_id }}" >
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
    <div>
        <div class="btn-group dropright" style="margin-right: 0; padding-right: 0; width: 100%">
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
    </div>
</div>
