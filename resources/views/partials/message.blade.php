
    @if(Auth::user()->userable->regular_user_id == $message->sender_id)
        <p class="chat_my_message">{{$message->body}}</p>
    @else 
    <div>
<img 
    @if (isset($image) && $image !== null)
        src="{{$image->file_path}}"
    @else
    src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png"
    @endif
    alt="" class="rounded-circle" style="max-width:2%; max-height: 2%;" align="left"/>
    <h6 style="border: 0; padding: 0; text-decoration:none; color:inherit"> <a href="/users/<?= $message->sender_id?>" style="text-decoration:none; color:inherit"> {{$user_name}}</a> </h6>
</div>
        <p class="chat_other_message">{{$message->body}}</p>
    @endif