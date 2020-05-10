@extends('layouts.uconnect_basic')

@section('content')


<script src="https://js.pusher.com/5.0/pusher.min.js"></script>
<script src="{{ asset('js/echo.iife.js') }}"></script>
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

<article class="chat" data-id="{{ $chat->chat_id }}">
    <div id="full_page" class="d-flex flex-column no-gutters vh-100" style="padding: 0">
        <section class="container-fluid no-gutters" style="flex: 1 1 auto">
            <section class="row" style="height: 100%">
                <section id="chats" class="col-md-3" style="height: 100%; padding: 0; border-width: 0; border-right-width: 0.1em; border-style:solid; border-color: sandybrown">
                    <header id="search_chat" class="row" style="margin: 0; padding: 0; width: 100%; height: 6.5%; border-color: sandybrown; border-width: 0; border-bottom-width: 0.1em; border-style: solid">
                        <form class="form-inline"  style="width: 100%; justify-content:center;">
                            <div class="input-group" style="margin-left:5px;margin-right:5px;border-width: 0.05em; border-color: lightgrey; border-radius: 2em; border-style:solid; background-color: white">
                                <input type="text" required class="form-control" placeholder="Search" aria-label="Search messages" aria-describedby="search-messages-button" style="border-width: 0; border-top-left-radius: inherit; border-bottom-left-radius: inherit;">
                                <div class="input-group-append" style="border-radius: inherit">
                                    <button class="btn btn-outline-light fa fa-search fa-flip-horizontal" type="submit" id="search-messages-button" style="background-color: sandybrown; border-top-left-radius: inherit; border-bottom-left-radius: inherit;"></button>
                                </div>
                            </div>
                        </form>
                    </header>
                    <div class="col" style="height: 87%; justify-content:flex-start; padding: 0">
                        @each('partials.chat', Auth::user()->chats, 'chat')
                    </div>
                    <footer id="create_chat" class="row" style="margin: 0; padding: 0; width: 100%; height: 6.5%">
                        <button class="btn" type="button" style="margin: 0; padding: 0; width: 100%; color: white; background-color: sandybrown; border-radius: 0;">
                            <p id="create_group_message"><i class="fa fa-plus"></i>&nbsp;Create Group Chat</p>
                        </button>
                    </footer>
                </section>

                <section id="opened_message" class="col-md-9 d-flex flex-column" style="height: 100%">
                    <header class="row" id="chat_info">
                        <img class="card-img" src="images/placeholder.png" alt="" style="width:2.5em; height:2.5em ; border-radius:50%" onclick="window.location.href='./profile.php'"/>
                        <h2>{{$chat->chat_name}}</h2>
                    </header>

                    <section id="messages_col" class="d-flex flex-column" style="flex-grow:1">
                        @each('partials.message', $messages, 'message')

                        <script>
                            window.Echo.channel('chat.{{$chat->chat_id}}')
                            .listen('NewMessage', (e) => {
                                var idUser = {{Auth::id()}}
                                let new_message = document.createElement("P");
                                if (idUser == e.message.sender_id) {
                                    new_message.className = "chat_my_message";
                                }                        
                                else {
                                    new_message.className = "chat_other_message";
                                }

                                new_message.innerHTML = `${e.message.body}`;

                                document.getElementById("messages_col").appendChild(new_message);
                            });
                        </script>
                    </section>

                    <footer class="row" id="send_message" style="border-width: 0; border-top-width: 0.1em; border-style:solid; border-color: sandybrown; height: 6.5%;">
                        <img class="chat_user_image" src="images/placeholder.png" alt=""  onclick="window.location.href='/users/me'"/>
                        <form id="newmessage" class="form-inline" style="max-width: 90%; width: 90%; justify-content:center;">
                            <div class="input-group chat_message_input" style="width:98%">
                                <textarea type="text" required class="form-control" placeholder="Write a message..." aria-label="msg-write" aria-describedby="send-message-button" style="border-width: 0; border-top-left-radius: inherit; border-bottom-left-radius: inherit;"></textarea>
                                <div class="input-group-append" style="border-radius: inherit">
                                    <button type="submit" class="btn btn-outline-light fa fa-caret-left fa-flip-horizontal" id="send-message-button" style="background-color: sandybrown; border-top-left-radius: inherit; border-bottom-left-radius: inherit;"></button>
                                </div>
                            </div>
                        </form>
                    </footer>
                </section>
            </section>
        </section>
    </div>
</article>


@endsection