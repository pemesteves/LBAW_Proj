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
                <a class="navbar-brand" href="/<?php if($is_admin) echo 'admin'; else echo 'feed';?>">
                    <h1>UConnect <span class="fa fa-graduation-cap"></span></h1>
                </a> <!-- whitesmoke -->
                <?php if(!$is_admin){?>
                <form class="form-inline">
                    <div class="input-group">
                        <input type="text" required class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search-button">
                        <div class="input-group-append">
                            <button class="btn btn-outline-light fa fa-search fa-flip-horizontal" type="submit" id="search-button"></button>
                        </div>
                    </div>
                </form>
                <button id="navbar_pers_info_mobile" onclick="show_pers_info()"><span class="fa fa-id-card"></span></button>
                <?php } ?>
                <div id="navbar_pers_info" class="btn-group" 
                    <?php if($is_admin){ ?>    
                    style="opacity: 1; display: block; width: auto;"    
                    <?php }?>
                >
                    <?php if(!$is_admin){ ?>
                    
                    <div class="btn-group">
                        <button class="btn btn-outline-light dropdown-toggle dropdown-toggle-split fa fa-bell" 
                            type="button" id="notificationDrop" data-toggle="dropdown" 
                            aria-haspopup="true" aria-expanded="false"></button>
                        <div class="dropdown-menu dropdown-menu-lg-right" style=" min-width:350px;padding:0px" aria-labelledby="notificationDrop" id="notif">
                            <p style='margin-left:10%;margin-top:auto;margin-bottom:auto' >Notifications</p>
                            <div class="dropdown-divider" style="margin-bottom:0px"></div>
                            <div style="max-height:200px;overflow-x: hidden;">

                            @if (count($notifications) == 0) 
                                <p id="no_notif" style='margin-left:10%;margin-top:auto;margin-bottom:auto' >No notifications yet</p>
                            @else
                                @each("partials.notification",$notifications,"notification")
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
                                    document.getElementById("no_notif").style.display = 'none';
                                    document.getElementById("notif").insertBefore(new_notification, document.getElementById("notif").childNodes[0].nextSibling);
                                });

                            </script>
                            
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-light fa fa-envelope" onclick="window.location.href='/chats'"></button>
                    <?php } ?>
                    <button type="button" class="btn btn-outline-light" onclick="window.location.href='<?php if($is_admin) echo '/admin'; else echo '/users/me'; ?>'"
                        <?php if($is_admin){ ?>    
                        style="opacity: 1; display: block;"    
                        <?php }?>
                    ><img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" alt="John"/>
                    @if (Auth::check()) 
                        {{ Auth::user()->name }} 
                    @endif
                    </button>
                    <?php /*if(!$is_admin){*/ ?>    
                        <div class="btn-group">
                        <button class="btn btn-outline-light dropdown-toggle dropdown-toggle-split" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">            
                                <div class="navbar-nav">
                                    <a class="dropdown-item" href="#"><span class="fa fa-adjust"></span>&nbsp;&nbsp;Dark Mode</a>
                                    <a class="dropdown-item" href="/groups/create"><span class="fa fa-users"></span>&nbsp;Create Group</a>
                                    @if (isset($can_create_events) && $can_create_events)
                                        <a class="dropdown-item" href="/events/create"><span class="fa fa-calendar"></span>&nbsp;&nbsp;Create Event</a>
                                    @endif
                                    <a class="dropdown-item" href="/about"><span class="fa fa-info-circle"></span>&nbsp;&nbsp;About Us</a>
                                    <a class="dropdown-item" href="#"><span class="fa fa-cog"></span>&nbsp;&nbsp;Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/logout') }}"><span class="fa fa-sign-out"></span>&nbsp;Logout</a>
                                </div>
                            </div>
                        </div>
                    <?php /*}*/ ?>
                </div>
            </nav>
@endsection