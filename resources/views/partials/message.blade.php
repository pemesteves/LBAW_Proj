    @if(Auth::user()->id == $message->sender_id)
        <p class="chat_my_message">{{$message->body}}</p>
    @else 
        <p class="chat_other_message">{{$message->body}}</p>