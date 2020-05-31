@extends('layouts.uconnect_template')


@section('nav_bar')

<script src="https://js.pusher.com/5.0/pusher.min.js"></script>
<script src="{{ asset('js/echo.js') }}"></script>
<script>
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '05ddfe6c26eaafb78b1b',
    cluster: 'mt1',
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
                                    <option>All</option>
                                    <option>Users</option>
                                    <option>Events</option>
                                    <option>Groups</option>
                                    <option>Posts</option>
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
                                    let new_notification = document.createElement('div');
                                    new_notification.classList.add('card', 'mb');
                                    new_notification.setAttribute('style',"margin-bottom:0px;border-radius:0px;");

                                    new_notification.innerHTML = ` 
                                        <a href="${e.notification.link}" style="text-decoration: none; color:black">
                                        <div class="row no-gutters">
                                            <div class="col-sm">
                                                <div class="card text-center" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                    <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" alt="..." style="border-radius:50%; max-width:3rem; padding:0.1rem">
                                                </div>
                                            </div>
                                            <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                                <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                    
                                                    <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;">
                                                        ${e.notification.description}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a> `
                                    let no_notif = document.getElementById("no_notif");
                                    if(no_notif) no_notif.style.display = 'none';
                                    document.getElementById("notificationDiv").insertBefore(new_notification, document.getElementById("notificationDiv").childNodes[0]);
                                    let count = document.querySelector('#notifications_count');
                                    count.style.display = 'inline-block';
                                    count.textContent = parseInt(count.textContent) + 1;
                                });

                            </script>
                            
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-light fa fa-envelope" onclick="window.location.href='/chats'"></button>
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

                            alt="User profile image"/>
                        @endif
                        @if (Auth::check()) 
                            {{ Auth::user()->name }} 
                        @endif
                    </button> 
                    <div class="btn-group">
                        <button class="btn btn-outline-light dropdown-toggle dropdown-toggle-split" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">            
                            <div class="navbar-nav">
                                    <a class="dropdown-item" href="#"><span class="fa fa-adjust"></span>&nbsp;&nbsp;Dark Mode</a>
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