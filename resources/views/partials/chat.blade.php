<article class="card-mb-3 chat_user_info_article" id="chats_view_{{$chat->chat_id}}" >
        <a href = "/chats/{{$chat->chat_id}}#bottom_chat" style="color: inherit; text-decoration: none;">
        <div class="row chat_user_info_article_div" style="margin-left:15px;flex-wrap:nowrap">
            
                <img class="card-img" src="https://image.flaticon.com/icons/svg/166/166258.svg" alt="chat_image" style="width:2.5em;height:2.5em; border-radius:50%"/>
                @if(Auth::user()->userable->not_seen($chat)!=0)
                <h5 class='not_seen_count' style='border-radius:50%;background-color:lavender;width:15px;height:15px;position:relative;text-align: center;line-height: 7px;'>
                        {{Auth::user()->userable->not_seen($chat)}}
                </h5>
                @else
                <h5 class='not_seen_count' style='visibility:hidden;border-radius:50%;background-color:lavender;width:15px;height:15px;position:relative;text-align: center;line-height: 7px;'>
                        {{Auth::user()->userable->not_seen($chat)}}
                </h5>
                @endif
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
                        @if(count($chat->in_chat)>3)
                                <img  style="width:30px;height:30px;border-radius:50%;float:left;position:relative;left:-45px" 
                                src="https://comunicadores.info/wp-content/uploads/2014/12/very-basic-plus-icon.png" class="mx-auto" alt="user"/>
                        @endif
                </div>
            
        </div>
        </a>
        
        <script>
                window.Echo.channel('chat.{{$chat->chat_id}}')
                .listen('NewMessage', (e) => {
                        console.log('aqui');
                        console.log(e);
                        @if(!$current_chat or $chat->chat_id != $current_chat->chat_id)
                                let count = document.querySelector('#chats_view_{{$chat->chat_id}} h5.not_seen_count');
                                count.textContent = parseInt(count.textContent)+1;
                                count.style.visibility='visible';
                        @endif
                        let article = document.querySelector('#chats_view_{{$chat->chat_id}}');
                        let parent = article.parentElement;
                        article.remove();
                        parent.insertAdjacentElement('afterbegin',article); 
                });
                </script> 
    </article> 