<div class="card mb" style="margin-bottom:0px;border-radius:0px;">
    <a href="{{$notification->link}}" style="text-decoration: none; color:black">
        <div class="row no-gutters">
            <div class="col-sm">
                <div class="card text-center" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                    <img @if ($notification->triggerUser->image() !== null)
                            src="{{$notification->triggerUser->image()->file_path}}"
                        @else
                            src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png"
                        @endif class="card-img-top mx-auto d-block" alt="user_image" style="border-radius:50%; height:3rem;width:3rem; padding:0.1rem">
                </div>
            </div>
            <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                    
                    <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;">
                        {{$notification->description}}
                    </p>
                    <p class="card-text" style="margin-bottom:0rem; float: right;margin-right:0.1rem;"><small class="text-muted" style="margin-bottom:0rem">{{date('d-m-Y', strtotime($notification->date))}}</small>, <small class="text-muted" style="margin-bottom:0.2rem">{{date('H:i', strtotime($notification->date))}}</small></p>
                </div>
            </div>
        </div>
    </a> 
</div>