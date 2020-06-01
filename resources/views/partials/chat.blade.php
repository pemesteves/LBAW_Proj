<article class="card-mb-3 chat_user_info_article" >
        <a href = "/chats/{{$chat->chat_id}}#bottom_chat" style="color: inherit; text-decoration: none;">
        <div class="row chat_user_info_article_div" style="margin-left:15px;flex-wrap:nowrap">
            
                <img class="card-img" src="https://image.flaticon.com/icons/svg/166/166258.svg" alt="chat_image" style="width:2.5em;height:2.5em; border-radius:50%"/>
            
               <h2 class="card-title" style="margin-left:10px; margin-right:10px;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;">{{$chat->chat_name}}</h2>
               <div class='chat_user_images'> 
                        @for ($i = 0; $i < 3 and $i < count($chat->in_chat); $i++)
                                <img style="width:30px;height:30px;border-radius:50%;float:left;position:relative;left:{{-15*$i}}px;"
                                @if (object_get($chat->in_chat[$i]->image(), "image_id"))
                                        src="{{object_get($chat->in_chat[$i]->image(), "file_path")}}"
                                @else
                                        src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                                @endif
                                class="mx-auto" alt="user"/>
                        @endfor 
                        @if(count($chat->in_chat)>=3)
                                <img  style="width:30px;height:30px;border-radius:50%;float:left;position:relative;left:-45px" 
                                src="https://comunicadores.info/wp-content/uploads/2014/12/very-basic-plus-icon.png" class="mx-auto" alt="user"/>
                        @endif
                </div>
            
        </div>
        </a>
    </article> 