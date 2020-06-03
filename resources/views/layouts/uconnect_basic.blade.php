@extends('layouts.uconnect_template')


@section('nav_bar')

<script src="https://js.pusher.com/5.0/pusher.min.js"></script>
<script src="{{ asset('js/echo.js') }}"></script>
<script>
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '05ddfe6c26eaafb78b1b',
    cluster: 'mt1',
    encrypted: false,
    wsPort: 6001,
    forceTLS: true
});
</script>

<meta name="csrf-token" content="{{ csrf_token() }}" />

<nav class="navbar navbar-dark navbar-bar">

                @if(!Auth::user()->isAdmin())
                    <a class="navbar-brand" href="/feed">
                @else
                    <a class="navbar-brand" href="/admin">
                @endif
                    <h2>UConnect <span class="fa fa-graduation-cap"></span></h2>
                </a> <!-- whitesmoke -->
                @if(!Auth::user()->isAdmin())
                    <form class="form-inline" method="get" action="/search">
                        <div class="input-group">
                            <div class="input-group-append">
                                <select class="form-control" required id="filter" name="filter">
                                    <option <?php if(isset($_GET['filter']) && $_GET['filter'] === 'All') echo 'selected';?>>All</option>
                                    <option <?php if(isset($_GET['filter']) && $_GET['filter'] === 'Users') echo 'selected';?>>Users</option>
                                    <option <?php if(isset($_GET['filter']) && $_GET['filter'] === 'Events') echo 'selected';?>>Events</option>
                                    <option <?php if(isset($_GET['filter']) && $_GET['filter'] === 'Groups') echo 'selected';?>>Groups</option>
                                    <option <?php if(isset($_GET['filter']) && $_GET['filter'] === 'Posts') echo 'selected';?>>Posts</option>
                                </select>
                            </div>
                            <input type="text" name='search' required class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search-button">
                            <div class="input-group-append">
                                <button class="btn btn-outline-light fa fa-search fa-flip-horizontal" type="submit" id="search-button"></button>
                            </div>
                        </div>
                    </form>
                    <button id="navbar_pers_info_mobile" onclick="show_pers_info()"><span class="fa fa-id-card"></span></button>
                @endif
                <div id="navbar_pers_info" class="btn-group">
                @if (!Auth::user()->isAdmin())
                    
                    <div class="btn-group">
                        <button class="btn btn-outline-light  dropdown-toggle-split fa fa-bell" 
                            type="button" id="notificationDrop" data-toggle="dropdown" 
                            aria-haspopup="true" aria-expanded="false">
                        </button>
                        @if(Auth::user()->userable->numberOfNotifications() > 0)
                        <h5 id='notifications_count' style='display:inline-block;position:absolute;left:22px;top:13px;background-color:lavender;
                            border-radius:50%;width: 15px;height: 15px;text-align: center;line-height: 13px;'>
                        @else
                        <h5 id='notifications_count' style='display:none;position:absolute;left:22px;top:13px;background-color:lavender;
                            border-radius:50%;width: 15px;height: 15px;text-align: center;line-height: 13px;'>
                        @endif
                            {{Auth::user()->userable->numberOfNotifications()}}
                        </h5>
                        <div class="dropdown-menu dropdown-menu-lg-right" style=" min-width:350px;padding:0px" aria-labelledby="notificationDrop" id="notif">
                            <p id="notifBar" style='margin-left:10%;margin-top:auto;margin-bottom:auto' >Notifications</p>
                            <div class="dropdown-divider" style="margin-bottom:0px"></div>
                            <div id="notificationDiv" style="max-height:200px;overflow-x: hidden;">

                            @if (count(Auth::user()->userable->notifications) == 0) 
                                <p id="no_notif" style='margin-left:10%;margin-top:auto;margin-bottom:auto' >No notifications yet</p>
                            @else
                                @each("partials.notification",Auth::user()->userable->notifications,"notification")
                            @endif

                            </div>

                            <script>
                                window.Echo.channel('notifiedUser.{{Auth::user()->userable->regular_user_id}}')
                                .listen('NewNotification', (e) => {
                                    if(e.notification.origin_user_id == {{Auth::user()->userable->regular_user_id}})
                                        return;
                                    let new_notification = document.createElement('div');
                                    new_notification.classList.add('card', 'mb','notification');
                                    new_notification.setAttribute('style',"margin-bottom:0px;border-radius:0px;");
                                    if(e.image == null){
                                    new_notification.innerHTML = ` 
                                        <a href="${e.notification.link}" style="text-decoration: none; color:black">
                                            <div class="row no-gutters">
                                                <div class="col-sm">
                                                    <div class="card text-center img_container" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                        <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" alt="user_image" style="border-radius:50%; max-width:3rem; padding:0.1rem">
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                                    <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                        
                                                        <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;">
                                                            ${e.notification.description}
                                                        </p>
                                                        <p class="card-text" style="margin-bottom:0rem; float: right;margin-right:0.1rem;">
                                                        <small style="margin-bottom:0rem">
                                                        now</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a> `
                                    }else{
                                        new_notification.innerHTML = ` 
                                        <a href="${e.notification.link}" style="text-decoration: none; color:black">
                                            <div class="row no-gutters">
                                                <div class="col-sm">
                                                    <div class="card text-center img_container" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                    <img src="${e.image.file_path}" class="card-img-top mx-auto d-block" alt="user_image" style="border-radius:50%; max-width:3rem; padding:0.1rem">
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                                    <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                        
                                                        <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;">
                                                            ${e.notification.description}
                                                        </p>
                                                        <p class="card-text" style="margin-bottom:0rem; float: right;margin-right:0.1rem;">
                                                        <small style="margin-bottom:0rem">
                                                        now</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a> `
                                    }
                                    let no_notif = document.getElementById("no_notif");
                                    if(no_notif) no_notif.style.display = 'none';
                                    document.getElementById("notificationDiv").insertBefore(new_notification, document.getElementById("notificationDiv").childNodes[0]);
                                    let count = document.querySelector('#notifications_count');
                                    count.style.display = 'inline-block';
                                    count.textContent = parseInt(count.textContent) + 1;
                                });

                            </script>
                            @foreach(Auth::user()->userable->events as $event)
                            <script>
                                window.Echo.channel('notifiedEvent.{{$event->event_id}}')
                                .listen('NewNotification', (e) => {
                                    if(e.notification.origin_user_id == {{Auth::user()->userable->regular_user_id}})
                                        return;
                                    let new_notification = document.createElement('div');
                                    new_notification.classList.add('card', 'mb','notification');
                                    new_notification.setAttribute('style',"margin-bottom:0px;border-radius:0px;");
                                    if(e.image == null){
                                    new_notification.innerHTML = ` 
                                        <a href="${e.notification.link}" style="text-decoration: none; color:black">
                                            <div class="row no-gutters">
                                                <div class="col-sm">
                                                    <div class="card text-center img_container" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                        <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" alt="user_image" style="border-radius:50%; max-width:3rem; padding:0.1rem">
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                                    <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                        
                                                        <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;">
                                                            ${e.notification.description}
                                                        </p>
                                                        <p class="card-text" style="margin-bottom:0rem; float: right;margin-right:0.1rem;">
                                                        <small style="margin-bottom:0rem">
                                                        now</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a> `
                                    }else{
                                        new_notification.innerHTML = ` 
                                        <a href="${e.notification.link}" style="text-decoration: none; color:black">
                                            <div class="row no-gutters">
                                                <div class="col-sm">
                                                    <div class="card text-center img_container" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                    <img src="${e.image.file_path}" class="card-img-top mx-auto d-block" alt="user_image" style="border-radius:50%; max-width:3rem; padding:0.1rem">
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                                    <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                        
                                                        <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;">
                                                            ${e.notification.description}
                                                        </p>
                                                        <p class="card-text" style="margin-bottom:0rem; float: right;margin-right:0.1rem;">
                                                        <small style="margin-bottom:0rem">
                                                        now</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a> `
                                    }
                                    let no_notif = document.getElementById("no_notif");
                                    if(no_notif) no_notif.style.display = 'none';
                                    document.getElementById("notificationDiv").insertBefore(new_notification, document.getElementById("notificationDiv").childNodes[0]);
                                    let count = document.querySelector('#notifications_count');
                                    count.style.display = 'inline-block';
                                    count.textContent = parseInt(count.textContent) + 1;
                                });

                            </script>
                            @endforeach
                            @foreach(Auth::user()->userable->groups as $group)
                            <script>
                                window.Echo.channel('notifiedGroup.{{$group->group_id}}')
                                .listen('NewNotification', (e) => {
                                    if(e.notification.origin_user_id == {{Auth::user()->userable->regular_user_id}})
                                        return;
                                    let new_notification = document.createElement('div');
                                    new_notification.classList.add('card', 'mb','notification');
                                    new_notification.setAttribute('style',"margin-bottom:0px;border-radius:0px;");
                                    if(e.image == null){
                                    new_notification.innerHTML = ` 
                                        <a href="${e.notification.link}" style="text-decoration: none; color:black">
                                            <div class="row no-gutters">
                                                <div class="col-sm">
                                                    <div class="card text-center img_container" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                        <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" alt="user_image" style="border-radius:50%; max-width:3rem; padding:0.1rem">
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                                    <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                        
                                                        <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;">
                                                            ${e.notification.description}
                                                        </p>
                                                        <p class="card-text" style="margin-bottom:0rem; float: right;margin-right:0.1rem;">
                                                        <small style="margin-bottom:0rem">
                                                        now</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a> `
                                    }else{
                                        new_notification.innerHTML = ` 
                                        <a href="${e.notification.link}" style="text-decoration: none; color:black">
                                            <div class="row no-gutters">
                                                <div class="col-sm">
                                                    <div class="card text-center img_container" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                    <img src="${e.image.file_path}" class="card-img-top mx-auto d-block" alt="user_image" style="border-radius:50%; max-width:3rem; padding:0.1rem">
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                                    <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                        
                                                        <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;">
                                                            ${e.notification.description}
                                                        </p>
                                                        <p class="card-text" style="margin-bottom:0rem; float: right;margin-right:0.1rem;">
                                                        <small style="margin-bottom:0rem">
                                                        now</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a> `
                                    }
                                    let no_notif = document.getElementById("no_notif");
                                    if(no_notif) no_notif.style.display = 'none';
                                    document.getElementById("notificationDiv").insertBefore(new_notification, document.getElementById("notificationDiv").childNodes[0]);
                                    let count = document.querySelector('#notifications_count');
                                    count.style.display = 'inline-block';
                                    count.textContent = parseInt(count.textContent) + 1;
                                });

                            </script>
                            @endforeach
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-light fa fa-envelope" onclick="window.location.href='/chats'"></button>
                        @if(Auth::user()->userable->total_not_seen() > 0)
                        <h5 id='notifications_count' style='display:inline-block;position:absolute;left:22px;top:13px;background-color:lavender;
                            border-radius:50%;width: 15px;height: 15px;text-align: center;line-height: 13px;'>
                        @else
                        <h5 id='notifications_count' style='display:none;position:absolute;left:22px;top:13px;background-color:lavender;
                            border-radius:50%;width: 15px;height: 15px;text-align: center;line-height: 13px;'>
                        @endif
                            {{Auth::user()->userable->total_not_seen()}}
                        </h5>
                    </div>
                    
                @endif
                    @if(Auth::user()->isAdmin())
                        <button type="button" class="btn btn-outline-light" onclick="window.location.href='/admin'">
                    @else
                        <button type="button" class="btn btn-outline-light" onclick="window.location.href='/users/me'">
                    @endif
                        @if(!Auth::user()->isAdmin())
                            <img 
                            @if (Auth::user()->userable->image() !== null)
                                src="{{Auth::user()->userable->image()->file_path}}"
                            @else
                                src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                            @endif    
                            alt="user_image"/>
                        @endif
                        @if (Auth::check()) 
                            {{ Auth::user()->name }} 
                        @endif
                    </button> 
                    <div class="btn-group">
                        <button class="btn btn-outline-light dropdown-toggle dropdown-toggle-split" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">            
                            <div class="navbar-nav">
                                @if(!Auth::user()->dark_mode)
                                    <a class="dropdown-item" href="/dark_mode"><span class="fa fa-adjust"></span>&nbsp;&nbsp;Dark Mode</a>
                                @else
                                    <a class="dropdown-item" href="/light_mode"><span class="fa fa-adjust"></span>&nbsp;&nbsp;Light Mode</a>
                                @endif
                                @if(!Auth::user()->isAdmin())   
                                    <a class="dropdown-item" href="/groups/create"><span class="fa fa-users"></span>&nbsp;Create Group</a>
                                    @if (isset($can_create_events) && $can_create_events)
                                        <a class="dropdown-item" href="/events/create"><span class="fa fa-calendar"></span>&nbsp;&nbsp;Create Event</a>
                                    @endif
                                    <a class="dropdown-item" href="/about"><span class="fa fa-info-circle"></span>&nbsp;&nbsp;About Us</a>
                                    <a class="dropdown-item" href="/settings"><span class="fa fa-cog"></span>&nbsp;&nbsp;Settings</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                <a class="dropdown-item" href="{{ url('/logout') }}"><span class="fa fa-sign-out"></span>&nbsp;Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
@endsection