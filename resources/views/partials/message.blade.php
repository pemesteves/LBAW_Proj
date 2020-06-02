
    @if(Auth::user()->userable->regular_user_id == $message->sender_id)
        <p data-id='{{$message->message_id}}' class="chat_my_message">{{$message->body}}</p>
    @else 
    <div data-id='{{$message->message_id}}' style="margin-left:10px">
        <div class="mytooltip">
            <a href='/users/{{$message->user->regular_user_id}}'>
                <img 
                    @if ($message->user->image() !== null)
                        src="{{$message->user->image()->file_path}}"
                    @else
                        src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png"
                    @endif
                    alt="author_image" class="rounded-circle" style="width:40px;height:40px;display:inline-block;margin-right:10px" align="left"/>
            </a>
            <span class="mytooltiptext">{{$message->user->user->name}}</span>
        </div>
        <p class="chat_other_message" style='display:inline-block '>{{$message->body}}</p>
    </div>    
    @endif