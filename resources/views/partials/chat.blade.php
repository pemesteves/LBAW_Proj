<article class="card-mb-3 chat_user_info_article" >
        <a href = "/chats/{{$chat->chat_id}}" style="color: inherit; text-decoration: none;">
        <div class="row chat_user_info_article_div" style="margin-left:15px">
            
                <img class="card-img" src="https://image.flaticon.com/icons/svg/166/166258.svg" alt="" style="width:2.5em;height:2.5em; border-radius:50%"/>
            
            
               <h2 class="card-title" style="margin-left:10px; margin-right:40px">{{$chat->chat_name}}</h2>
               <?php $i=0 ?>
               @if (sizeof($members_info) > 3)
                @foreach ($members_info as $member_info)
                  @if ($i < 3)
                <?php $image = $member_info->image()?>
                        <img 
                        @if (isset($image) && $image !== null)
                                src="{{$image->file_path}}"
                        @else
                        src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png"
                        @endif
                        alt="" class="rounded-circle" style="max-width:8%; max-height: 2%;" align="left"/>

                        <?php $i++; ?>
                  @else
                  <img    
                        src="https://comunicadores.info/wp-content/uploads/2014/12/very-basic-plus-icon.png"
                        alt="" class="rounded-circle" style="max-width:8%; max-height: 2%;" align="left"/>
                        @break
                  @endif
                @endforeach
               @else
                @foreach ($members_info as $member_info)
                <?php $image = $member_info->image()?>     
                <img 
                        @if (isset($image) && $image !== null)
                                src="{{$image->file_path}}"
                        @else
                        src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png"
                        @endif
                        alt="" class="rounded-circle" style="max-width:2%; max-height: 2%;" align="left"/>
                        </div>
                 @endforeach
                 <img    
                        src="https://comunicadores.info/wp-content/uploads/2014/12/very-basic-plus-icon.png"
                        alt="" class="rounded-circle" style="max-width:8%; max-height: 2%;" align="left"/>
               @endif
            
        </div>
        </a>
    </article> 