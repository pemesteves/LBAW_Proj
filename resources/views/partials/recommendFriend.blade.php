<div class="card mb" style="margin-bottom:0px;border-radius:0px;margin-right:0.5rem">
    <a href="./users/{{$user->regular_user_id}}" style="text-decoration: none; color:black">
        <div class="row no-gutters">
            <div class="col-sm">
                <div class="card text-center" style="border-right:none;border-bottom:none;border-top:none;border-radius:0;height:100%;">
                    <img 
                    @if ($user->image() !== null)
                        src="{{$user->image()->file_path}}"
                    @else
                        src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                    @endif
                    class="card-img-top mx-auto d-block" 
                    alt="user_image" style="border-radius:50%; max-width:3rem; padding:0.1rem;padding-top:0.2rem">
                </div>
            </div>
            <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                    <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;display:inline-block;">
                        {{$user->user->name}}
                    </p>
                    <span class="btn btn-light add_friend" data-id='{{$user->regular_user_id}}' 
                        style="background-color: rgba(0,0,150,.03);float:right; margin-right:0.5rem;margin-top:0.2rem;font-size:0.9rem ">
                        Add Friend
                    </span>
                    <p class="card-text" style="margin-bottom:0rem; float: left;margin-left:0.2rem;">
                        <small class="text-muted" style="margin-bottom:0rem"> {{count($user->friendsInCommun(Auth::user()->userable))}} friends in commun </small>
                    </p>
                </div>
            </div>
        </div>
    </a> 
</div>