<article class="card-mb-3 chat_user_info_article" >
        <a href = "/chats/{{$chat->chat_id}}" style="color: inherit; text-decoration: none;">
        <div class="row chat_user_info_article_div" style="margin-left:15px">
            
                <img class="card-img" src="https://image.flaticon.com/icons/svg/166/166258.svg" alt="" style="width:2.5em;height:2.5em; border-radius:50%"/>
            
            
               <h2 class="card-title" style="margin-left:10px">{{$chat->chat_name}}</h2>
               
               @if ($members_count > 3) {
                @for($i = 0; $i < 3; $i++)
                  $members_info[$i]->
                @endfor 
               }
               @else
                @for($i = 0; $i < $members_count; $i++)
                  $members_info[$i]->
                @endfor 
               @endif
            
        </div>
        </a>
    </article> 