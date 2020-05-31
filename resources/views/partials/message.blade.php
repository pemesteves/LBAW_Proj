
    @if(Auth::user()->userable->regular_user_id == $message->sender_id)
        <p class="chat_my_message">{{$message->body}}</p>
    @else 
    <div>
<img 
    @if ($message->user->image() !== null)
        src="{{$message->user->image()->file_path}}"
    @else
        src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png"
    @endif
    alt="author_image" class="rounded-circle" style="max-width:2%; max-height: 2%;" align="left"/>
    <h6 style="border: 0; padding: 0; text-decoration:none; color:inherit"> <a href="/users/<?= $message->sender_id?>" style="text-decoration:none; color:inherit">
     {{$message->user->user->name}}
    </a> </h6>
</div>
        <p class="chat_other_message">{{$message->body}}</p>
    @endif