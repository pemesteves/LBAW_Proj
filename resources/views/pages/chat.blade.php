<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <!-- CSRF token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Barlow|Cormorant+Garamond&display=swap">

        <!-- Costume Css -->

        <link href="{{ asset('css/error.css') }}" rel="stylesheet">
        @if(!Auth::check())
            <link href="{{ asset('css/auth_theme.css') }}" rel="stylesheet">
        @else
            @if(Auth::user()->dark_mode)
                <link href="{{ asset('css/dark_theme.css') }}" rel="stylesheet">
            @else
                <link href="{{ asset('css/light_theme.css') }}" rel="stylesheet">
            @endif
        @endif

        
        @if(isset($css))
            @foreach($css as $c)
                <link href='{{ asset("css/$c") }}' rel="stylesheet">
            @endforeach
        @endif

        <script src="{{ asset('js/navbar_mobile.js') }}" defer></script>        
        <script src="{{ asset('js/app.js') }}" defer> </script>
        <script src="{{ asset('js/input_validation.js') }}" defer> </script>
        
        
        

        @if(isset($js))
            @foreach($js as $j)
                <script src='{{ asset("js/$j") }}' defer> </script>
            @endforeach
        @endif

        <style>
            body {
                background-color: rgba(244, 166, 98, 0.05);
                font-family: 'Barlow', Arial, Helvetica, sans-serif
            } 

            h1, h2, h3, h4, h5, h6 {
                font-family: 'Cormorant Garamond', 'Times New Roman', Times, serif;
                font-weight: bold;
            }
        </style>

        <title>UConnect: We're getting there</title>
    </head>
    <body>
        <div id="full_page" style="padding: 0;display: flex; flex-flow: column;height: 100vh;">
            <div>
                
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

            </div>
            
            <div id='feedback'>
            </div>           
                                
            <div id="chat_content" @if ($chat) 
                data-id="{{ $chat->chat_id }}"
                @endif
            >
                    
                @if(Session::has("success_message"))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?=
                    Session::get("success_message") 
                    ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div id='chat_sidebar'>
                    <header id="search_chat" style="margin: 0; padding: 0; width: 100%; height: 55px; border-color: sandybrown; border-width: 0; border-bottom-width: 0.1em; border-style: solid">
                        <form class="form-inline" method="post" style="width: 100%; justify-content:center;margin-top:7px">
                            <div class="input-group" style="margin-left:5px;margin-right:5px;border-width: 0.05em; border-color: lightgrey; border-radius: 2em; border-style:solid; background-color: white">
                                <input type="text" required class="form-control" placeholder="Search" aria-label="Search messages" aria-describedby="search-messages-button" style="border-width: 0; border-top-left-radius: inherit; border-bottom-left-radius: inherit;">
                                <div class="input-group-append" style="border-radius: inherit">
                                    <button class="btn btn-outline-light fa fa-search fa-flip-horizontal" type="submit" id="search-messages-button" style="background-color: sandybrown; border-top-left-radius: inherit; border-bottom-left-radius: inherit;"></button>
                                </div>
                            </div>
                        </form>
                    </header>
                    <div id='user_chats' style='flex: 1;overflow: auto;overflow-x: hidden !important;;display:flex;flex-flow:column;margin-top:2px;margin-bottom:2px'>
                        @if(count(Auth::user()->userable->chats) == 0) 
                            <p>You have no chats! If you want to connect with your friends, create one.</p>
                        @endif
                        @foreach(Auth::user()->userable->chats as $chatt)
                                    @include('partials.chat',['chat' => $chatt,'current_chat' => $chat])
                        @endforeach 
                        
                    </div>
                    <footer id="send_message" style="border-width: 0; border-top-width: 0.1em; border-style:solid; border-color: sandybrown;">
                        <button class="btn" type="button" style="height:60px;margin: 0; padding: 0; width: 100%; color: white; background-color: sandybrown; border-radius: 0;" data-toggle="modal" data-target="#addChatModal">
                            <p id="create_group_message"><i class="fa fa-plus"></i>&nbsp;Create Group Chat</p>
                        </button>
                    </footer>
                </section>
                </div>
                <div id='chat_general'>
                    @if($chat)
                        <header id="chat_header">
                            <input type="image" src="https://image.flaticon.com/icons/svg/166/166258.svg" data-toggle="modal" data-target="#viewMembersModal" id="chat_img" 
                                style="float:left;width:2.5em;height:2.5em;border-radius:50%;margin-left:4px;margin-top:4px"/>
                            <h2 style='display:inline-block;text-overflow: ellipsis;white-space: nowrap;'>{{$chat->chat_name}}</h2>
                            <div id='add_members' style='margin-right:20px'>
                                <button id='add_members_chat' class='btn btn-light' style='border-radius:50%;width:40px;height:40px' data-toggle="modal" data-target="#addMemberModal">
                                    <span class="fa fa-plus"></span>
                                </button>
                                members
                            </div>
                        </header>
                        <div id='messages_col'>
                            @each('partials.message', $messages, 'message')
                            <script>
                            window.Echo.channel('chat.{{$chat->chat_id}}')
                            .listen('NewMessage', (e) => {
                                var idUser = {{Auth::user()->userable->regular_user_id}}
                                let new_message = document.createElement("P");
                                if (idUser == e.message.sender_id) {
                                    new_message.className = "chat_my_message";
                                    new_message.innerHTML = `${e.message.body}`;
                                    document.getElementById("messages_col").appendChild(new_message);
                                    
                                }                        
                                else {
                                    let new_message_other = document.createElement("div");
                                    new_message_other.setAttribute('style','margin-left:10px');
                                    if(e.image.localeCompare("") == 0){
                                        new_message_other.innerHTML += 
                                        `<div class="mytooltip">
                                                                    <a href='/users/${e.id}'>
                                                                    <img src=\"https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png\"  alt="author_image" class="rounded-circle" style="width:40px;height:40px;display:inline-block;margin-right:10px" align="left"/>
                                            </a>
                                                <span class="mytooltiptext">${e.user_name}</span>
                                            </div>
                                            <p class="chat_other_message" style='display:inline-block '>${e.message.body}</p>
                                        </div>  `;
                                    }else{
                                        new_message_other.innerHTML += 
                                        `<div class="mytooltip">
                                                                    <a href='/users/${e.id}'>
                                                                    <img src=\"${e.image}\" alt="author_image" class="rounded-circle" style="width:40px;height:40px;display:inline-block;margin-right:10px" align="left"/>
                                            </a>
                                                <span class="mytooltiptext">${e.user_name}</span>
                                            </div>
                                            <p class="chat_other_message" style='display:inline-block '>${e.message.body}</p>
                                        </div>  `;
                                    }
                                    document.getElementById("messages_col").appendChild(new_message_other);
                                }
                                sendAjaxRequest('put', '/api/chats/{{$chat->chat_id}}/clear', null, null);
                                location.href = "#";
                                location.href = "#bottom_chat";
                                
                            });
                            </script> 

                            <span id='bottom_chat'></span>
                        </div>
                        <footer id="send_message" style="border-width: 0; border-top-width: 0.1em; border-style:solid; border-color: sandybrown;">
                            <img class="chat_user_image" @if (Auth::user()->userable->image() !== null)
                                    src="{{Auth::user()->userable->image()->file_path}}"
                                @else
                                    src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                                @endif   alt=""  onclick="window.location.href='/users/{{Auth::user()->userable->regular_user_id}}'" 
                            style='display:inline-block;width:50px;height:50px' >
                            <form id="newmessage" label='message' class="form-inline" style="max-width: 90%; width: 90%; justify-content:center;display:inline-block">
                                <div class="input-group chat_message_input" style="width:98%">
                                    <input type="text" label='message' required class="form-control" placeholder="Write a message..." aria-label="msg-write" aria-describedby="send-message-button" style="border-width: 0; border-top-left-radius: inherit; border-bottom-left-radius: inherit;"></textarea>
                                    <div class="input-group-append" style="border-radius: inherit">
                                        <button type="submit" class="btn btn-outline-light fa fa-caret-left fa-flip-horizontal" id="send-message-button" style="background-color: sandybrown; border-top-left-radius: inherit; border-bottom-left-radius: inherit;"></button>
                                    </div>
                                </div>
                            </form>
                        </footer>
                        <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="addMemberModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addMemberModalLabel">Add new members to "{{$chat->chat_name}}"</h5>
                                        <input type="hidden" id="report_id">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="col-form-label">Member:</label>
                                            <input type="text" name='name' autocomplete="off" label='name' class="form-control" id="new_member_name">
                                        </div>
                                        <div id='members_search'>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="viewMembersModal" tabindex="-1" role="dialog" aria-labelledby="viewMembersModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addChatModalLabel">Chat members</h5>
                                        <input type="hidden" id="report_id">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    @foreach($in_chat as $member_in_chat)
                                        <div class="card mb member_card" style="margin-bottom:0px;border-radius:0px;" data-id="{{ $member_in_chat->regular_user_id }}">
                                            <div class="row no-gutters">
                                                <div class="col-md" style="flex-grow:1; max-width:100%; text-align: left;">
                                                    <a href="../users/{{$member_in_chat->regular_user_id}}" style="text-decoration: none; color:black">
                                                        <div class="row no-gutters">
                                                            <div class="col-sm">
                                                                <div class="card text-center" style="border-right:none;border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                                    <img 
                                                                    @if (object_get($member_in_chat->image(), "image_id"))
                                                                    src="{{object_get($member_in_chat->image(), "file_path")}}"
                                                                    @else
                                                                    src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                                                                    @endif 
                                                                    class="card-img-top mx-auto d-block" 
                                                                    alt="..." style="border-radius:50%; width:3rem;height:3rem; padding:0.1rem;padding-top:0.2rem">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                                                <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                                    <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;display:inline-block;">
                                                                        {{$member_in_chat->user->name}}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a> 
                                                </div>
                                                @if($member_in_chat->regular_user_id == Auth::user()->userable->regular_user_id)
                                                    <div class="col-sm" style="flex-grow:0; max-width:100%; text-align: left;" data-id='{{$member_in_chat->regular_user_id}}'>
                                                        <span class="btn btn-light leave_chat_button" id="leave_chat_button" data-id='{{$member_in_chat->regular_user_id}}' 
                                                            style="background-color: rgba(0,0,150,.03);float:right; margin-right:0.5rem;margin-top:0.2rem;font-size:0.9rem ">
                                                            Leave
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @if(count($in_chat) == 1) 
                            <script>
                                window.onload = function() {
                                    $('#addMemberModal').modal();
                                };
                            </script>
                        @endif

                    @endif

                    <div class="modal fade" id="addChatModal" tabindex="-1" role="dialog" aria-labelledby="addChatModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addChatModalLabel">Add chat name</h5>
                                    <input type="hidden" id="report_id">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="chat_create">
                                        <div class="form-group">
                                            <label class="col-form-label">Chat:</label>
                                            <input type="text" name='name' required="required" autocomplete="off" label='name' class="form-control" id="new_chat_name">
                                            <input type="submit" id="new_chat_name_submit" style="position: absolute; left: -9999px"/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>

        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>
